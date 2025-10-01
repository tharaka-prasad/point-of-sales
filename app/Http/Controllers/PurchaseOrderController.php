<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PurchaseOrder;
use App\Models\Supplier;

class PurchaseOrderController extends Controller
{
    // Display list of POs
    public function index()
    {
        $menu = 'PO';
        //loadingding supplier and items on the table
        $pos = PurchaseOrder::with('supplier')->paginate(10); // eager load supplier

        // Calculate grand_total for each PO
        foreach ($pos as $po) {
            $po->grand_total = $po->quantity * $po->rate; // calculate from PO columns
        }

        return view('po.index', compact('menu', 'pos'));
    }

    // Show form to create new PO
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
            'po_number' => 'required|string|max:50|unique:pos,po_number',
            'purchase_company' => 'required|string|max:255',
            'supplier_id' => 'required|integer|exists:suppliers,id',
            'description' => 'nullable|string',
            'contact_no' => 'nullable|string|max:20',
            'quantity' => 'required|integer|min:1',
            'rate' => 'required|numeric|min:0',
            'status' => 'required|string|in:draft,pending,complete,rejected',
        ]);

        $validated['grand_total'] = $validated['quantity'] * $validated['rate'];

        // Create PO using mass assignment
        PurchaseOrder::create($validated);

        return redirect()->route('po.index')->with('success', 'Purchase Order created successfully.');
    }

    // Show specific PO
    public function show(PurchaseOrder $po)
    {
        return view('po.show', compact('po'));
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
        $validated = $request->validate([
            'purchase_company' => 'required|string|max:255',
            'supplier_id' => 'required|integer|exists:suppliers,id',
            'description' => 'nullable|string',
            'contact_no' => 'nullable|string|max:20',
            'quantity' => 'required|integer|min:1',
            'rate' => 'required|numeric|min:0',
            'status' => 'required|string|in:draft,pending,complete,rejected',
        ]);

        $validated['grand_total'] = $validated['quantity'] * $validated['rate'];

        $po->update($validated);

        return redirect()->route('po.index')->with('success', 'Purchase Order updated successfully.');
    }

    // Delete PO
    public function destroy(PurchaseOrder $po)
    {
        $po->delete();
        return redirect()->route('po.index')->with('success', 'Purchase Order deleted successfully.');
    }
}
