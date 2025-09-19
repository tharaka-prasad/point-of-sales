<?php
namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
                (object)['id'=>1,'name'=>'Walk-In Customer','address'=>'Udabadda Ella, Panwwewa, Maliththa','contact'=>'0771234567']
            ]);
        }

        if ($products->isEmpty()) {
            $products = collect([
                (object)['id'=>1,'name'=>'T200 25KG','sale_price'=>120.00,'stock'=>100],
                (object)['id'=>2,'name'=>'T200 50KG','sale_price'=>7000.00,'stock'=>50]
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
    $status = $request->action === 'print' ? 'complete' : 'draft';

    // Create Sale
    $sale = Sale::create([
        'member_id'   => $request->member_id ?? null,
        'total_item'  => $request->total_item,
        'total_price' => $request->total_price,
        'discount'    => $request->discount ?? 0,
        'pay'         => $request->pay ?? 0,
        'accepted'    => $request->accepted ?? 0,
        'user_id'     => Auth::id(),
        'status'      => $status,
    ]);

    // Save details
    foreach ($request->products as $product) {
        SaleDetail::create([
            'sale_id'    => $sale->id,
            'product_id' => $product['id'],
            'sale_price' => $product['sale_price'],
            'amount'     => $product['amount'],
            'discount'   => $product['discount'] ?? 0,
            'sub_total'  => $product['sub_total'],
        ]);

        // Reduce stock if complete
        if ($status === 'complete') {
            Product::where('id', $product['id'])
                ->decrement('stock', $product['amount']);
        }
    }

    // If print → go to print page
    if ($request->action === 'print') {
        return redirect()->route('cashier.print', $sale->id);
    }

    return redirect()->route('cashier.index')
        ->with('success', "Sale {$status} successfully!");
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
