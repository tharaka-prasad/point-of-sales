<?php

namespace App\Http\Controllers;

use App\Models\Grn;
use App\Models\Supplier;
use App\Models\Product;
use App\Models\GrnItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GrnController extends Controller
{
    /**
     * Display a listing of GRNs.
     */
    public function index()
    {
        $menu = 'GRN';
        $suppliers = Supplier::all();
        $products  = Product::all();
        $grns = Grn::with('items.product', 'supplier')->latest()->paginate(10);

        return view('grn.index', compact('menu', 'suppliers', 'products', 'grns'));
    }

    /**
     * Show the form for creating a new GRN.
     */
    public function create()
    {
        $menu = 'Create GRN';
        $suppliers = Supplier::all();
        $products  = Product::all();

        return view('grn.form', compact('menu', 'suppliers', 'products'));
    }

    /**
     * Store a newly created GRN in storage.
     */
 public function store(Request $request)
{
    $validated = $request->validate([
        'grn_no' => 'required|unique:grns,grn_no',
        'date' => 'required|date',
        'supplier' => 'required|string',
        'po_no' => 'nullable|string',
        'invoice_no' => 'nullable|string',
        'general_remarks' => 'nullable|string',
        'items' => 'required|array|min:1',
        'items.*.code' => 'required|string',
        'items.*.desc' => 'required|string',
        'items.*.received' => 'required|numeric',
        'items.*.accepted' => 'required|numeric',
        'items.*.price' => 'required|numeric',
    ]);

    DB::transaction(function () use ($validated) {
        // Save main GRN
        $grn = Grn::create([
            'grn_no' => $validated['grn_no'],
            'date' => $validated['date'],
            'supplier' => $validated['supplier'],
            'po_no' => $validated['po_no'] ?? null,
            'invoice_no' => $validated['invoice_no'] ?? null,
            'general_remarks' => $validated['general_remarks'] ?? null,
        ]);

        // Save items
        foreach ($validated['items'] as $item) {
            $grn->items()->create([
                'item_code' => $item['code'],
                'description' => $item['desc'],
                'uom' => $item['uom'] ?? null,
                'qty_ordered' => $item['ordered'] ?? 0,
                'qty_received' => $item['received'],
                'qty_accepted' => $item['accepted'],
                'qty_rejected' => $item['rejected'] ?? 0,
                'unit_price' => $item['price'],
                'total_price' => $item['accepted'] * $item['price'],
                'remarks' => $item['remarks'] ?? null,
            ]);
        }
    });

    return redirect()->route('grn.index')->with('success', 'GRN and items saved successfully.');
}

    /**
     * Display the specified GRN.
     */
    // public function show(Grn $grn)
    // {
    //     $menu = 'View GRN';
    //     $suppliers = Supplier::all();
    //     $products  = Product::all();
    //     $grn->load('items.product', 'supplier');

    //     return view('grn.show', compact('menu', 'grn', 'suppliers', 'products'));
    // }

    /**
     * Show the form for editing the specified GRN.
     */
    // public function edit(Grn $grn)
    // {
    //     $menu = 'Edit GRN';
    //     $suppliers = Supplier::all();
    //     $products  = Product::all();
    //     $grn->load('items.product', 'supplier');

    //     return view('grn.edit', compact('menu', 'grn', 'suppliers', 'products'));
    // }

    /**
     * Update the specified GRN in storage.
     */
    public function update(Request $request, Grn $grn)
    {
        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'date' => 'required|date',
            'items' => 'required|array',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|numeric|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        $grn->update([
            'supplier_id'       => $request->supplier_id,
            'purchase_order_id' => $request->purchase_order_id ?? null,
            'date'              => $request->date,
            'total_amount'      => $request->total_amount ?? 0,
            'tax_amount'        => $request->tax_amount ?? 0,
            'discount_amount'   => $request->discount_amount ?? 0,
            'grand_total'       => $request->grand_total ?? 0,
            'status'            => $request->status ?? $grn->status,
            'remarks'           => $request->remarks,
            'updated_by'        => auth()->id(),
        ]);

        // delete old items & re-add
        $grn->items()->delete();

        foreach ($request->items as $item) {
            $grn->items()->create([
                'product_id'   => $item['product_id'],
                'quantity'     => $item['quantity'],
                'unit_price'   => $item['unit_price'],
                'total_price'  => $item['quantity'] * $item['unit_price'],
                'batch_number' => $item['batch_number'] ?? null,
                'expiry_date'  => $item['expiry_date'] ?? null,
                'remarks'      => $item['remarks'] ?? null,
            ]);
        }

        return redirect()->route('grn.index')->with('success', 'GRN updated successfully');
    }

    /**
     * Remove the specified GRN from storage.
     */
    public function destroy(Grn $grn)
    {
        $grn->items()->delete();
        $grn->delete();

        return redirect()->route('grn.index')->with('success', 'GRN deleted successfully');
    }
}
