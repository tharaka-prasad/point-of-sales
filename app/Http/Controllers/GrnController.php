<?php

namespace App\Http\Controllers;

use App\Models\Grn;
use App\Models\Supplier;
use App\Models\Product;
use App\Models\GrnItem;
use Illuminate\Http\Request;

class GrnController extends Controller
{
    public function index()
    {
        $menu = 'GRN';
        $suppliers = Supplier::all();
        $products  = Product::all();
        $grns = Grn::with('items.product', 'supplier')->latest()->paginate(10);

        // make sure this is the blade you actually use (resources/views/grn/grn.blade.php)
        return view('grn.grn', compact('menu', 'suppliers', 'products', 'grns'));
    }

    public function create()
    {
        $menu = 'Create GRN';
        $suppliers = Supplier::all();
        $products  = Product::all();

        return view('grn.create', compact('menu', 'suppliers', 'products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'date' => 'required|date',
            'items' => 'required|array',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|numeric|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        $grn = Grn::create([
            'supplier_id' => $request->supplier_id,
            'purchase_order_id' => $request->purchase_order_id ?? null,
            'date' => $request->date,
            'total_amount' => $request->total_amount ?? 0,
            'tax_amount' => $request->tax_amount ?? 0,
            'discount_amount' => $request->discount_amount ?? 0,
            'grand_total' => $request->grand_total ?? 0,
            'status' => 'pending',
            'remarks' => $request->remarks,
            'created_by' => auth()->id(),
        ]);

        foreach ($request->items as $item) {
            $grn->items()->create([
                'product_id'  => $item['product_id'],
                'quantity'    => $item['quantity'],
                'unit_price'  => $item['unit_price'],
                'total_price' => $item['quantity'] * $item['unit_price'],
                'batch_number'=> $item['batch_number'] ?? null,
                'expiry_date' => $item['expiry_date'] ?? null,
                'remarks'     => $item['remarks'] ?? null,
            ]);
        }

        return redirect()->route('grn.index')->with('success', 'GRN created: ' . $grn->grn_number);
    }

    public function show(Grn $grn)
    {
        $menu = 'View GRN';
        $suppliers = Supplier::all(); // optional, if view needs it
        $products  = Product::all();  // optional
        $grn->load('items.product', 'supplier');

        return view('grn.show', compact('menu', 'grn', 'suppliers', 'products'));
    }
}

