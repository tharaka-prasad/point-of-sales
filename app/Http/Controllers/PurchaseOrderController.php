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

        // Calculate grand_total for each PO
        foreach ($pos as $po) {
            $po->grand_total = $po->quantity * $po->rate; // calculate from PO columns
        }

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
    public function store(Request $request)
    {
        $validated = $request->validate([
            'po_number'        => 'nullable|string', # required
            'purchase_company' => 'nullable|string', # required
            'supplier_id'      => 'nullable|exists:suppliers,id', # required
            'description'      => 'nullable|string',
            'contact_no'       => 'nullable|string',
            'total_price'      => 'nullable|numeric',
            'status'           => 'string',
            'items'            => 'nullable|array', # required
            'items.*.item_name'=> 'nullable|string', # required
            'items.*.category' => 'nullable|string',
            'items.*.uom'      => 'nullable|string',
            'items.*.qty'      => 'nullable|numeric', # required
            'items.*.rate'     => 'nullable|numeric', # required
            'items.*.remarks'  => 'nullable|string',
        ]);

        DB::transaction(function () use ($validated) {

            // 1️⃣ Create Purchase Order (main record)
            $po = PurchaseOrder::create([
                'po_number'        => $validated['po_number'],
                'supplier_id'      => $validated['supplier_id'],
                'description'      => $validated['description'] ?? null,
                //'rate'             => collect($validated['items'])->avg('rate'),
                'status'           => $validated['status'] ?? 'draft',
            ]);

            // 2️⃣ Loop through PO Items
            foreach ($validated['items'] as $item) {
                $poItem = PurchaseOrderItem::create([
                    'purchase_order_id' => $po->id, // you must have this foreign key in po_items table
                    'item_name'         => $item['item_name'],
                    'category'          => $item['category'] ?? null,
                    'uom'               => $item['uom'] ?? null,
                    'qty'               => $item['qty'],
                    'rate'              => $item['rate'],
                    'remarks'           => $item['remarks'] ?? null,
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
    public function update(Request $request, PurchaseOrder $po)
    {
        $request->validate([
            'supplier_id'      => 'required|exists:suppliers,id',
            'po_number'        => 'required|string|max:50',
            'purchase_company' => 'required|string|max:255',
            'description'      => 'nullable|string',
            'contact_no'       => 'nullable|string|max:20',
            'status'           => 'nullable|string|max:50',
            'items'            => 'required|array',
            'items.*.item_name'=> 'required|string|max:255',
            'items.*.category' => 'nullable|string|max:255',
            'items.*.uom'      => 'nullable|string|max:50',
            'items.*.qty'      => 'required|numeric|min:1',
            'items.*.rate'     => 'required|numeric|min:0',
            'items.*.remarks'  => 'nullable|string',
        ]);

        // Update main PO table
        $po->update([
            'po_number'        => $request->po_number,
            'purchase_company' => $request->purchase_company,
            'supplier_id'      => $request->supplier_id,
            'description'      => $request->description,
            'contact_no'       => $request->contact_no,
            'status'           => $request->status ?? $po->status,
        ]);

        // Delete old items and re-insert new ones
        $po->items()->delete();

        foreach ($request->items as $item) {
            $po->items()->create([
                'item_name' => $item['item_name'],
                'category'  => $item['category'] ?? null,
                'uom'       => $item['uom'] ?? null,
                'qty'       => $item['qty'],
                'rate'      => $item['rate'],
                'remarks'   => $item['remarks'] ?? null,
            ]);
        }

        return redirect()->route('purchase-orders.index')->with('success', 'Purchase Order updated successfully');
    }
 public function destroy(pos $po)
    {
        $pos->items()->delete();
        $pos->delete();

        return redirect()->route('grn.index')->with('success', 'POS deleted successfully');
    }
}



