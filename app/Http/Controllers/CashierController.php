<?php
namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\Printer;

class CashierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Fetch customers & products
        $customers = Member::all();
        $products  = Product::all();

        // Send dummy data if tables are empty
        if ($customers->isEmpty()) {
            $customers = collect([
                (object) ['id' => 1, 'name' => 'Walk-In Customer', 'address' => 'Udabadda Ella, Panwwewa, Maliththa', 'contact' => '0771234567'],
            ]);
        }

        if ($products->isEmpty()) {
            $products = collect([
                (object) ['id' => 1, 'name' => 'T200 25KG', 'sale_price' => 120.00, 'stock' => 100],
                (object) ['id' => 2, 'name' => 'T200 50KG', 'sale_price' => 7000.00, 'stock' => 50],
            ]);
        }
        // dd($products);

        return view('cashier.cashier', compact('customers', 'products'))->with('menu', 'Cashier');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // ✅ Prevent duplicate sale record (session check)
        if ($request->session()->has('last_sale_id')) {
            $sale = Sale::find($request->session()->get('last_sale_id'));
            if ($sale) {
                return redirect()->route('cashier.print', $sale->id);
            }
        }

        $status = $request->action === 'print' ? 'complete' : 'draft';

        // Calculate totals
        $totalItem  = $request->total_item ?? count($request->products ?? []);
        $totalPrice = $request->total_price ?? array_sum(array_map(fn($p) => $p['sub_total'], $request->products ?? []));
        $productIds = $request->products ? array_column($request->products, 'id') : [];

        // Create sale
        $sale = Sale::create([
            'member_id'       => $request->member_id ?? null,
            'total_item'      => $totalItem,
            'total_price'     => $totalPrice,
            'discount'        => $request->discount ?? 0,
            'pay'             => $request->pay ?? 0,
            'accepted'        => $request->accepted ?? 0,
            'user_id'         => auth()->id(),
            'status'          => $status,
            'product_ids'     => $productIds,
            'return_products' => [],
        ]);

        // ✅ Save details + reduce stock only if complete
        if ($status === 'complete' && $request->products) {
            foreach ($request->products as $product) {
                $amount = $product['amount'] ?? 1; // default to 1 if missing

                SaleDetail::create([
                    'sale_id'    => $sale->id,
                    'product_id' => $product['id'],
                    'sale_price' => $product['sale_price'],
                    'amount'     => $amount,
                    'discount'   => $product['discount'] ?? 0,
                    'sub_total'  => $product['sub_total'] ?? ($amount * ($product['sale_price'] ?? 0)),
                ]);

                // Reduce stock safely
                $productModel = Product::findOrFail($product['id']);
                $productModel->stock -= $amount;
                $productModel->save();
            }
        }

        // ✅ Save last sale ID in session (avoid duplicate save)
        $request->session()->put('last_sale_id', $sale->id);

        if ($request->action === 'print') {
            return redirect()->route('cashier.print', $sale->id);
        }

        return redirect()->route('cashier.index')->with('success', "Sale {$status} successfully!");
    }

    public function print($id)
    {
        $sale = Sale::with(['member', 'items.product', 'cashier'])->findOrFail($id);

        $created_at = $sale->created_at
            ? $sale->created_at->format('Y-m-d H:i')
            : now()->format('Y-m-d H:i');

        // Calculate totals
        $subtotal = $sale->total_price;
        $discount = $sale->discount ?? 0;
        $total    = $subtotal - $discount;
        $paid     = $sale->pay;
        $change   = $paid - $total;

        session()->forget('last_sale_id');

        // ✅ Cash drawer pulse (optional)
        try {
            $connector = new WindowsPrintConnector("EPSON");
            $printer   = new Printer($connector);
            $printer->pulse();
            $printer->close();
        } catch (\Exception $e) {
            // \Log::error("Cash drawer not opened: " . $e->getMessage());
        }

        return view('cashier.print', [
            'sale'       => $sale,
            'created_at' => $created_at,
            'subtotal'   => $subtotal,
            'discount'   => $discount,
            'total'      => $total,
            'paid'       => $paid,
            'change'     => $change,
            'menu'       => 'Invoice',
        ]);
    }

    public function getDraftSales()
    {
        $drafts = Sale::with('member')
            ->where('status', 'draft')
            ->where('user_id', auth()->id()) // only show drafts created by current user
            ->orderBy('id', 'desc')
            ->get();

        return response()->json($drafts);
    }

    public function getDraftSale($id)
    {
        $draft = Sale::with(['member', 'details.product']) // eager load products from details
            ->where('id', $id)
            ->where('user_id', auth()->id()) // only owner can load
            ->firstOrFail();

        // Transform details to match frontend expected format
        $draftData = [
            'id'          => $draft->id,
            'member'      => $draft->member,
            'total_item'  => $draft->total_item,
            'total_price' => $draft->total_price,
            'pay'         => $draft->pay,
            'discount'    => $draft->discount,
            'products'    => $draft->details->map(fn($d) => [
                'id'         => $d->product_id,
                'name'       => $d->product->name ?? '',
                'sale_price' => $d->sale_price,
                'amount'     => $d->amount,
                'discount'   => $d->discount,
                'sub_total'  => $d->sub_total,
            ]),
        ];

        return response()->json($draftData);
    }
    // Step 1: Get all completed sales for a customer
    public function getCustomerSales($customerId)
    {
        $customer = Member::with('sales')->find($customerId);

        if (! $customer) {
            return response()->json(['message' => 'Customer not found'], 404);
        }

        return response()->json($customer->sales);
    }

    // Step 2: Get all returnable products in a selected sale
    public function getSaleProducts($saleId)
    {
        $details = SaleDetail::with('product:id,name,code')
            ->where('sale_id', $saleId)
            ->whereColumn('amount', '>', 'return_qty') // can still be returned
            ->get()
            ->map(function ($item) {
                return [
                    'product_id'     => $item->product_id,
                    'product_name'   => $item->product->name,
                    'sale_price'     => $item->sale_price,
                    'sold_qty'       => $item->amount,
                    'returnable_qty' => $item->amount - $item->return_qty,
                ];
            });

        return response()->json($details);
    }

    // ✅ Save return data
    public function storeReturn(Request $request)
    {
        $request->validate([
            'customer_id'           => 'required|exists:members,id',
            'products'              => 'required|array|min:1',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.qty'        => 'required|numeric|min:1',
            'products.*.price'      => 'required|numeric|min:0',
        ]);

        DB::transaction(function () use ($request) {
            foreach ($request->products as $item) {
                $detail = SaleDetail::whereHas('sale', function ($q) use ($request) {
                    $q->where('member_id', $request->customer_id);
                })
                    ->where('product_id', $item['product_id'])
                    ->first();

                if ($detail) {
                    // update returned qty
                    $detail->return_qty = ($detail->return_qty ?? 0) + $item['qty'];
                    $detail->save();
                }
            }

            // optional: record in Sale table for reference
            Sale::create([
                'member_id'       => $request->customer_id,
                'total_item'      => count($request->products),
                'total_price'     => collect($request->products)->sum(fn($p) => $p['price'] * $p['qty']),
                'discount'        => 0,
                'pay'             => 0,
                'accepted'        => 0,
                'user_id'         => auth()->id(),
                'status'          => 'return',
                'return_products' => $request->products,
            ]);
        });

        return response()->json(['message' => 'Return saved successfully.']);
    }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
