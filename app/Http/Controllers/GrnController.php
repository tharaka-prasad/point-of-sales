<?php
namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Grn;
use App\Models\GrnItems;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GrnController extends Controller
{
    // Display a listing of GRNs.
    public function index(){
        $menu      = 'GRN';
        $suppliers = Supplier::all();
        $products  = Product::all();

        // Eager load supplier, creator, and items
        $grns = Grn::with('items.product', 'supplier', 'creator')
            ->latest()
            ->paginate(10);

        return view('grn.index', compact('menu', 'suppliers', 'products', 'grns'));
    }

    /**
     * Show the form for creating a new GRN.
     */

    public function create()
    {
        $menu      = 'Create GRN';
        $suppliers = Supplier::all();
        return view('grn.form', compact('menu', 'suppliers'));
    }
    // Store new GRN
    public function store(Request $request)
    {
        $validated = $request->validate([
            'date'             => 'required|date',
            'supplier'         => 'required|exists:suppliers,id',
            'po_no'            => 'nullable|string',
            'invoice_no'       => 'required|string',
            'general_remarks'  => 'nullable|string',
            'grn_total'        => 'required|numeric',
            'items'            => 'nullable|array',
            'items.*.code'     => 'nullable|string', // barcode / product reference
            'items.*.desc'     => 'nullable|string',
            'items.*.uom'      => 'nullable|string',
            'items.*.remarks'  => 'nullable|string', // category name
            'items.*.ordered'  => 'nullable|numeric',
            'items.*.received' => 'nullable|numeric',
            'items.*.accepted' => 'nullable|numeric',
            'items.*.price'    => 'nullable|numeric',
        ]);

        DB::transaction(function () use ($validated) {

            // 1️⃣ Create GRN main record
            $grn = Grn::create([
                'date'            => $validated['date'],
                'supplier_id'     => $validated['supplier'],
                'po_no'           => $validated['po_no'],
                'invoice_no'      => $validated['invoice_no'],
                'general_remarks' => $validated['general_remarks'] ?? null,
                'grn_total'       => $validated['grn_total'],
                'created_by'      => auth()->id(),
            ]);

            // 2️⃣ Loop through items
            if (! empty($validated['items'])) {
                foreach ($validated['items'] as $item) {

                    // 2a. Handle Category (from remarks)
                    $categoryName = $item['remarks'] ?? 'Uncategorized';
                    $category     = Category::firstOrCreate(['name' => $categoryName]);

                    // 2b. Handle Product
                    $product = Product::where('code', $item['code'] ?? null)
                        ->orderBy('id', 'desc')
                        ->first();

                    // If product not exists or unit price changed → create new product
                    if (! $product || ($item['price'] && $item['price'] != $product->price)) {

                        // generate unique code if not provided
                        $uniqueCode = $item['code'] ?? uniqid('P-');
                        while (Product::where('code', $uniqueCode)->exists()) {
                            $uniqueCode = uniqid('P-');
                        }

                        $product = Product::create([
                            'code'        => $uniqueCode,
                            'name'        => $item['desc'] ?? 'Unnamed Product',
                            'category_id' => $category->id,
                            'price'       => $item['price'] ?? 0,
                            'sell_price'  => $item['price'] ?? 0,
                            'stock'       => 0,
                        ]);
                    }

                    // 2c. Update stock with accepted qty
                    $acceptedQty = $item['accepted'] ?? 0;
                    $product->stock += $acceptedQty;
                    $product->save();

                    // 2d. Save GRN Item
                    GrnItems::create([
                        'grn_id'       => $grn->id,
                        'product_id'   => $product->id,
                        'description'  => $item['desc'] ?? null,
                        'uom'          => $item['uom'] ?? null,
                        'qty_ordered'  => $item['ordered'] ?? 0,
                        'qty_received' => $item['received'] ?? 0,
                        'qty_accepted' => $acceptedQty,
                        'qty_rejected' => ($item['received'] ?? 0) - $acceptedQty,
                        'unit_price'   => $product->price,
                        'total'        => $acceptedQty * $product->price,
                        'remarks'      => $category->name,
                        'created_by'   => auth()->id(),
                    ]);
                }
            }
        });

        return redirect()->route('grn.index')->with('success', 'GRN saved successfully!');
    }

    /**
     * Display the specified GRN.
     */
    public function show($id)
    {
        $grn = Grn::with('supplier', 'items')->findOrFail($id);

        return view('grn.show', [
            'menu' => 'View GRN',
            'grn'  => $grn,
        ]);
    }

    /**
     * Show the form for editing the specified GRN.
     */
    public function edit($id)
    {
        $grn       = Grn::with('items.product', 'supplier')->findOrFail($id);
        $suppliers = Supplier::all();
        $menu      = 'Edit GRN';

        return view('grn.edit', compact('grn', 'suppliers', 'menu'));
    }

    /**
     * Update GRN.
     */
    public function update(Request $request, Grn $grn)
    {
        $validated = $request->validate([
            'date'             => 'required|date',
            'supplier'         => 'required|exists:suppliers,id',
            'po_no'            => 'required|string',
            'invoice_no'       => 'required|string',
            'general_remarks'  => 'nullable|string',
            'items'            => 'nullable|array',
            'items.*.code'     => 'nullable|string',
            'items.*.desc'     => 'nullable|string',
            'items.*.uom'      => 'nullable|string',
            'items.*.remarks'  => 'nullable|string',
            'items.*.ordered'  => 'nullable|numeric',
            'items.*.received' => 'nullable|numeric',
            'items.*.accepted' => 'nullable|numeric',
            'items.*.price'    => 'nullable|numeric',
        ]);

        DB::transaction(function () use ($validated, $grn) {

            // 1️⃣ Revert previous stock before deleting old items
            foreach ($grn->items as $oldItem) {
                $oldItem->product->stock -= $oldItem->qty_accepted;
                $oldItem->product->save();
            }
            $grn->items()->delete();

            // 2️⃣ Update GRN main info
            $grn->update([
                'date'            => $validated['date'],
                'supplier_id'     => $validated['supplier'],
                'po_no'           => $validated['po_no'],
                'invoice_no'      => $validated['invoice_no'],
                'general_remarks' => $validated['general_remarks'] ?? null,
                'updated_by'      => auth()->id(),
            ]);

            $totalAmount = 0;

            // 3️⃣ Handle GRN items
            if (! empty($validated['items'])) {
                foreach ($validated['items'] as $item) {

                    // Category
                    $categoryName = $item['remarks'] ?? 'Uncategorized';
                    $category     = Category::firstOrCreate(['name' => $categoryName]);

                    // Product
                    $product = Product::where('code', $item['code'] ?? null)
                        ->orderBy('id', 'desc')
                        ->first();

                    if (! $product || ($item['price'] && $item['price'] != $product->price)) {
                        $uniqueCode = $item['code'] ?? uniqid('P-');
                        while (Product::where('code', $uniqueCode)->exists()) {
                            $uniqueCode = uniqid('P-');
                        }

                        $product = Product::create([
                            'code'        => $uniqueCode,
                            'name'        => $item['desc'] ?? 'Unnamed Product',
                            'category_id' => $category->id,
                            'price'       => $item['price'] ?? 0,
                            'sell_price'  => $item['price'] ?? 0,
                            'stock'       => 0,
                        ]);
                    }

                    // Update stock
                    $acceptedQty = $item['accepted'] ?? 0;
                    $product->stock += $acceptedQty;
                    $product->save();

                    // GRN item
                    $lineTotal = $acceptedQty * ($item['price'] ?? 0);
                    $totalAmount += $lineTotal;

                    GrnItems::create([
                        'grn_id'       => $grn->id,
                        'product_id'   => $product->id,
                        'description'  => $item['desc'] ?? null,
                        'uom'          => $item['uom'] ?? null,
                        'qty_ordered'  => $item['ordered'] ?? 0,
                        'qty_received' => $item['received'] ?? 0,
                        'qty_accepted' => $acceptedQty,
                        'qty_rejected' => ($item['received'] ?? 0) - $acceptedQty,
                        'unit_price'   => $product->price,
                        'total'        => $lineTotal,
                        'remarks'      => $category->name,
                        'created_by'   => auth()->id(),
                    ]);
                }
            }

            // Update GRN total
            $grn->update(['grn_total' => $totalAmount]);
        });

        return redirect()->route('grn.index')->with('success', 'GRN updated successfully!');
    }

    /**
     * Remove the specified GRN from storage.
     */
    public function destroy(Grn $grn)
    {
        DB::transaction(function () use ($grn) {

            // 1️⃣ Revert stock for all items
            foreach ($grn->items as $item) {
                $product = $item->product;
                if ($product) {
                    $product->stock -= $item->qty_accepted;
                    if ($product->stock < 0) {
                        $product->stock = 0;
                    }
                    // prevent negative stock
                    $product->save();
                }
            }

            // 2️⃣ Delete all GRN items
            $grn->items()->delete();

            // 3️⃣ Delete GRN
            $grn->delete();
        });

        return redirect()->route('grn.index')->with('success', 'GRN deleted successfully!');
    }

}
