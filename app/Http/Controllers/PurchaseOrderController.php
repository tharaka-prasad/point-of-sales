<?php

namespace App\Http\Controllers;

use App\Models\GrnItem;
use App\Models\Grn;
use App\Models\Product;
use Illuminate\Http\Request;

class GrnItemController extends Controller
{
    /**
     * Display a listing of GRN items.
     */
    public function index()
    {
        $items = GrnItem::with(['grn', 'product'])->get();

        return view('grn_items.index', compact('items'));
    }

    /**
     * Show the form for creating a new GRN item.
     */
    public function create()
    {
        $grns = Grn::all();
        $products = Product::all();

        return view('grn_items.create', compact('grns', 'products'));
    }

    /**
     * Store a newly created GRN item.
     */
    public function store(Request $request)
    {
        $request->validate([
            'grn_id'       => 'required|exists:grns,id',
            'product_id'   => 'required|exists:products,id',
            'uom'          => 'required|string|max:50',
            'qty_ordered'  => 'required|numeric|min:0',
            'qty_received' => 'required|numeric|min:0',
            'qty_accepted' => 'nullable|numeric|min:0',
            'qty_rejected' => 'nullable|numeric|min:0',
            'unit_price'   => 'required|numeric|min:0',
            'remarks'      => 'nullable|string',
            'status'       => 'required|in:draft,complete,pending,reject',
            'description'  => 'nullable|string',
        ]);

        GrnItem::create([
            'grn_id'       => $request->grn_id,
            'product_id'   => $request->product_id,
            'uom'          => $request->uom,
            'qty_ordered'  => $request->qty_ordered,
            'qty_received' => $request->qty_received,
            'qty_accepted' => $request->qty_accepted ?? 0,
            'qty_rejected' => $request->qty_rejected ?? 0,
            'unit_price'   => $request->unit_price,
            'remarks'      => $request->remarks,
            'status'       => $request->status,
            'created_by'   => auth()->id(),
            'description'  => $request->description,
        ]);

        return redirect()->route('grn_items.index')->with('success', 'GRN Item added successfully.');
    }

    /**
     * Show the form for editing the specified GRN item.
     */
    public function edit(GrnItem $grnItem)
    {
        $grns = Grn::all();
        $products = Product::all();

        return view('grn_items.edit', compact('grnItem', 'grns', 'products'));
    }

    /**
     * Update the specified GRN item.
     */
    public function update(Request $request, GrnItem $grnItem)
    {
        $request->validate([
            'uom'          => 'required|string|max:50',
            'qty_ordered'  => 'required|numeric|min:0',
            'qty_received' => 'required|numeric|min:0',
            'qty_accepted' => 'nullable|numeric|min:0',
            'qty_rejected' => 'nullable|numeric|min:0',
            'unit_price'   => 'required|numeric|min:0',
            'remarks'      => 'nullable|string',
            'status'       => 'required|in:draft,complete,pending,reject',
            'description'  => 'nullable|string',
        ]);

        $grnItem->update($request->all());

        return redirect()->route('grn_items.index')->with('success', 'GRN Item updated successfully.');
    }

    /**
     * Remove the specified GRN item.
     */
    public function destroy(GrnItem $grnItem)
    {
        $grnItem->delete();

        return redirect()->route('grn_items.index')->with('success', 'GRN Item deleted successfully.');
    }
}
