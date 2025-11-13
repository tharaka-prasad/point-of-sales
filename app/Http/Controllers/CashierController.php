<?php
namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleDetail;
use Illuminate\Http\Request;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\Printer;

class CashierController extends Controller
{
    //Display a listing of the resource
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

        return view('cashier.cashier', compact('customers', 'products'))->with('menu', 'Cashier');
    }

     //Show the form for creating a new resource.
    public function create()
    {
        //
    }

    //Store a newly created resource in storage.
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

    // Return
    public function returnPage()
    {
        $menu      = 'Return';
        $customers = Member::all();
        return view('cashier.return', compact('customers', 'menu'));
    }

    // Get all sales of a customer
    public function getCustomerSales($customerId)
    {
        $customer = Member::with('sales')->find($customerId);

        if (! $customer) {
            return response()->json(['message' => 'Customer not found'], 404);
        }

        $sales = $customer->sales->map(function ($sale) {
            return [
                'id'          => $sale->id,
                'total_price' => $sale->total_price,
                'created_at'  => $sale->created_at->format('Y-m-d H:i:s'),
            ];
        });

        return response()->json([
            'balance' => $customer->balance ?? 0,
            'sales'   => $sales,
        ]);
    }

    // Get products for a sale
    public function getSaleProducts($saleId)
    {
        $saleDetails = SaleDetail::with('product')
            ->where('sale_id', $saleId)
            ->whereColumn('amount', '>', 'return_qty') // only returnable
            ->get();

        $products = $saleDetails->map(function ($detail) {
            return [
                'product_id'     => $detail->product_id,
                'product_name'   => $detail->product->name,
                'sale_price'     => $detail->sale_price, // <- corrected
                'returnable_qty' => $detail->amount - $detail->return_qty,
            ];
        });

        return response()->json($products);
    }

    public function storeReturn(Request $request)
    {
        $request->validate([
            'sale_id'               => 'required|exists:sales,id',
            'products'              => 'required|array|min:1',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.return_qty' => 'required|numeric|min:1',
        ]);

        $sale        = Sale::findOrFail($request->sale_id);
        $totalReturn = 0;

        foreach ($request->products as $prod) {
            $saleDetail = SaleDetail::where('sale_id', $request->sale_id)
                ->where('product_id', $prod['product_id'])
                ->first();

            $product = Product::find($prod['product_id']);

            if ($saleDetail && $product) {
                // Add returned qty to sale detail
                $saleDetail->return_qty += $prod['return_qty'];
                $saleDetail->save();

                // Add returned qty back to stock
                $product->stock += $prod['return_qty'];
                $product->save();

                // Calculate total return amount
                $totalReturn += $prod['return_qty'] * $saleDetail->sale_price;
            }
        }

        // Reduce sale total price by total return
        $sale->total_price -= $totalReturn;
        $sale->save();

        // Optional: update sale return_products column
        $sale->return_products = $sale->return_products ?? [];
        $sale->return_products = array_merge($sale->return_products, $request->products);
        $sale->save();

        return response()->json([
            'success'         => true,
            'total_return'    => $totalReturn,
            'updated_balance' => $sale->total_price, // Sale total after return
        ]);
    }

    // Display the specified resource.
    public function show(string $id)
    {
        //
    }

    // Show the form for editing the specified resource.
    public function edit(string $id)
    {
        //
    }

    // Update the specified resource in storage.
    public function update(Request $request, string $id)
    {
        //
    }

    // Remove the specified resource from storage.
    public function destroy(string $id)
    {
        //
    }
}
