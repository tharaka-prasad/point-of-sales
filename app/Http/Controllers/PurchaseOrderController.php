<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrder;
use Illuminate\Http\Request;

class PurchaseOrderController extends Controller
{
    public function index()
    {
        $menu = "PO";
        return view("po.index", compact("menu"));
    }

    public function data()
    {
        $purchaseOrders = PurchaseOrder::latest();

        return datatables()
            ->of($purchaseOrders)
            ->addIndexColumn()
            ->addColumn("action", function ($po) {
                return "
                <div class='btn-group'>
                    <button class='btn btn-xs btn-warning mr-3' onclick='editPurchaseOrder(`". route("po.show", $po->id) ."`)'><i class='fa fa-pencil-alt'></i></button>
                    <button class='btn btn-xs btn-danger' onclick='deletePurchaseOrder(`". route("po.destroy", $po->id) ."`)'><i class='fa fa-trash-alt'></i></button>
                </div>
                ";
            })
            ->rawColumns(["action"])
            ->make(true);
    }

    public function create()
    {
        //$menu = "PO";

        // get last PO and parse its numeric part (expects format like "PO-1000")
        $last = PurchaseOrder::orderBy('id', 'desc')->first();

        $nextNumber = 1000; // default start
        if ($last && preg_match('/PO-(\d+)/', $last->po_number, $m)) {
            $nextNumber = (int) $m[1] + 1;
        }

        // keep the numeric width (e.g. PO-1000)
        $nextPoNumber = 'PO-' . $nextNumber;

        return view('po.form', compact('menu', 'nextPoNumber'));
    }

    public function store(Request $request)
    {
        $po = PurchaseOrder::create($request->all());

        if ($po) {
            return response()->json("Purchase Order added successfully.", 201);
        }
    }

    public function show(string $id)
    {
        $po = PurchaseOrder::findOrFail($id);

        if ($po) {
            return response()->json($po);
        }
    }

    public function edit(string $id)
    {
        // using show() for modal data
    }

    public function update(Request $request, string $id)
    {
        $po = PurchaseOrder::findOrFail($id);

        if ($po) {
            $po->po_number     = $request->po_number;
            $po->company_name  = $request->company_name;
            $po->supplier_name = $request->supplier_name;
            $po->description   = $request->description;
            $po->contact_no    = $request->contact_no;
            $po->quantity      = $request->quantity;
            $po->rate          = $request->rate;
            $po->status        = $request->status;
            $po->save();

            return response()->json("Purchase Order updated successfully.");
        }
    }

    public function destroy(string $id)
    {
        $po = PurchaseOrder::findOrFail($id);

        if ($po) {
            $po->delete();
            return response()->json("Purchase Order deleted successfully.");
        }
    }
}
