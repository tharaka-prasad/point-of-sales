<?php
namespace App\Http\Controllers;

use App\Models\Kitchen;
use App\Models\Product;
use Illuminate\Http\Request;

class KitchenController extends Controller
{
    public function index()
    {
        $menu     = 'Kitchen';
        $kitchen  = Kitchen::latest()->get();
        $products = Product::orderBy('name')->get(); // For dropdown

        return view('kitchen.index', compact('kitchen', 'menu', 'products'));
    }

    public function create()
    {
        return view('kitchen.form');
    }

    public function store(Request $request)
    {
        $request->validate([
            'item_name'  => 'required|exists:products,id',
            'qty'        => 'required|numeric|min:0.01',
            'unit'       => 'nullable|string',
            'issue_date' => 'required|date',
            'issued_by'  => 'nullable|string',
            'meal_type'  => 'required|string|in:breakfast,lunch,dinner,all',
        ]);

        // Fetch product
        $product = Product::findOrFail($request->item_name);

        // Check available stock
        if ($product->stock < $request->qty) {
            return back()->with('error', 'Not enough stock to issue this item.');
        }

        // Reduce product stock
        $product->stock -= $request->qty;
        $product->save();

        // Save kitchen issue
        Kitchen::create([
            'item_name'  => $request->item_name,
            'qty'        => $request->qty,
            'unit'       => $request->unit,
            'issue_date' => $request->issue_date,
            'issued_by'  => $request->issued_by ?? auth()->user()->name ?? 'Admin',
            'meal_type'  => $request->meal_type,
        ]);

        return redirect()->route('kitchen.index')->with('success', 'Kitchen issue saved & stock updated!');
    }

    public function edit(Kitchen $kitchen)
    {
        $products = Product::orderBy('name')->get();
        return response()->json([
            'data'     => $kitchen,
            'products' => $products,
        ]);
    }

    public function update(Request $request, Kitchen $id)
    {
        $request->validate([
            'item_name'  => 'required|exists:products,id',
            'qty'        => 'required|numeric|min:0.01',
            'unit'       => 'nullable|string',
            'issue_date' => 'required|date',
            'issued_by'  => 'nullable|string',
            'meal_type'  => 'required|string|in:breakfast,lunch,dinner,all',
        ]);

        $product = Product::findOrFail($request->item_name);

        $oldQty = $id->qty;
        $newQty = $request->qty;

        if ($newQty > $oldQty) {
            $difference = $newQty - $oldQty;
            if ($product->stock < $difference) {
                return back()->with('error', 'Not enough stock to increase issue quantity.');
            }
            $product->stock -= $difference;
        } else {
            $difference = $oldQty - $newQty;
            $product->stock += $difference;
        }

        $product->save();

        $id->update([
            'item_name'  => $request->item_name,
            'qty'        => $newQty,
            'unit'       => $request->unit,
            'issue_date' => $request->issue_date,
            'issued_by'  => $request->issued_by ?? 'Admin',
            'meal_type'  => $request->meal_type,
        ]);

        return redirect()->route('kitchen.index')->with('success', 'Kitchen item updated successfully!');
    }

    public function destroy($id)
    {
        $kitchen = Kitchen::findOrFail($id);
        $kitchen->delete();

        return response()->json(['success' => true]);
    }

}
