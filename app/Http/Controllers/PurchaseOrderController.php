<?php
namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class PurchaseOrderController extends Controller
{
    // Display list of POs
    public function index()
    {
        $menu = 'PO';
        $suppliers = Supplier::all();
        $products = Product::all();

         // eager load supplier
        $pos = PurchaseOrder::with('product', 'supplier')
        ->latest()
        ->paginate(10);

        return view('po.index', compact('menu', 'suppliers', 'products', 'pos'));
    }

    // Show form to create new PO - no issue with that
    public function create()
    {
        $menu = 'PO';
        $suppliers = Supplier::all();

        // Generate next PO number
        $lastPO = PurchaseOrder::latest('id')->first();
        if ($lastPO) {
            $lastNumber = (int) str_replace('PO-', '', $lastPO->po_number);
            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = 1000; // starting PO number
        }
        $nextPoNumber = 'PO-' . $nextNumber;

        return view('po.form', compact('menu', 'suppliers', 'nextPoNumber'));
    }

    // Store new PO
 // Store new PO
public function store(Request $request)
{
    $validated = $request->validate([
        'po_number'        => 'nullable|string',
        'supplier_id'      => 'nullable|exists:suppliers,id',
        'description'      => 'nullable|string',
        'status'           => 'nullable|string',
        'items'            => 'required|array',
        'items.*.item_name'=> 'required|string',
        'items.*.category' => 'nullable|string',
        'items.*.uom'      => 'nullable|string',
        'items.*.qty'      => 'required|numeric|min:1',
        'items.*.rate'     => 'required|numeric|min:0',
        'items.*.remarks'  => 'nullable|string',
    ]);

    DB::transaction(function () use ($validated) {

        // 1️⃣ Create Purchase Order
        $grandTotal = collect($validated['items'])->sum(function($item) {
            return $item['qty'] * $item['rate'];
        });

        $po = PurchaseOrder::create([
            'po_number'   => $validated['po_number'],
            'supplier_id' => $validated['supplier_id'],
            'description' => $validated['description'] ?? null,
            'rate'        => collect($validated['items'])->avg('rate'),
            'grand_total' => $grandTotal,
            'status'      => $validated['status'] ?? 'draft',
        ]);

        // 2️⃣ Insert PO Items
        foreach ($validated['items'] as $item) {
            PurchaseOrderItem::create([
                'item_name' => $item['item_name'],
                'category'  => $item['category'] ?? null,
                'uom'       => $item['uom'] ?? null,
                'qty'       => $item['qty'],
                'rate'      => $item['rate'],
                'total'     => $item['qty'] * $item['rate'], // auto-calculated
                'remarks'   => $item['remarks'] ?? null,
            ]);
        }
    });

    return redirect()->route('po.index')->with('success', 'Purchase Order saved successfully!');
}


   // Show specific PO
public function show($id)
{
    $menu = 'PO';

    // Load PO with supplier and items
    $po = PurchaseOrder::with(['supplier', 'items'])->findOrFail($id);

    return view('po.show', compact('menu', 'po'));
}


    // Show form to edit PO
    public function edit(PurchaseOrder $po)
    {
        $suppliers = Supplier::all();
        return view('po.edit', compact('po', 'suppliers'));
    }

    // Update PO
 // Update existing PO
public function update(Request $request, PurchaseOrder $po)
{
    $validated = $request->validate([
        'po_number'        => 'required|string|max:50',
        'supplier_id'      => 'required|exists:suppliers,id',
        'description'      => 'nullable|string',
        'status'           => 'nullable|string',
        'items'            => 'required|array',
        'items.*.item_name'=> 'required|string|max:255',
        'items.*.category' => 'nullable|string|max:255',
        'items.*.uom'      => 'nullable|string|max:50',
        'items.*.qty'      => 'required|numeric|min:1',
        'items.*.rate'     => 'required|numeric|min:0',
        'items.*.remarks'  => 'nullable|string',
    ]);

    DB::transaction(function () use ($po, $validated) {

        // 1️⃣ Update main PO
        $grandTotal = collect($validated['items'])->sum(function($item) {
            return $item['qty'] * $item['rate'];
        });

        $po->update([
            'po_number'   => $validated['po_number'],
            'supplier_id' => $validated['supplier_id'],
            'description' => $validated['description'] ?? null,
            'rate'        => collect($validated['items'])->avg('rate'),
            'grand_total' => $grandTotal,
            'status'      => $validated['status'] ?? $po->status,
        ]);

        // 2️⃣ Delete old items & insert updated items
        $po->items()->delete();

        foreach ($validated['items'] as $item) {
            PurchaseOrderItem::create([
                'item_name' => $item['item_name'],
                'category'  => $item['category'] ?? null,
                'uom'       => $item['uom'] ?? null,
                'qty'       => $item['qty'],
                'rate'      => $item['rate'],
                'total'     => $item['qty'] * $item['rate'], // auto-calculated
                'remarks'   => $item['remarks'] ?? null,
            ]);
        }
    });

    return redirect()->route('po.index')->with('success', 'Purchase Order updated successfully!');
}


 public function destroy(pos $po)
    {
        $pos->items()->delete();
        $pos->delete();

        return redirect()->route('grn.index')->with('success', 'POS deleted successfully');
    }
}



