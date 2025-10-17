<?php
namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index()
    {
        $menu = "Supplier";

        return view("supplier.index", compact("menu"));
    }

    public function data()
    {
        $suppliers = Supplier::latest();
        $categories = Category::all();

        return datatables()
            ->of($suppliers)
            ->addIndexColumn()
            
            ->addColumn("action", function ($supplier) {
                return "
                <div class='btn-group'>
                    <button class='btn btn-xs btn-warning mr-3' onclick='editSupplier(`". route("supplier.update", $supplier->id) ."`)'><i class='fa fa-pencil-alt'></i></button>
                    <button class='btn btn-xs btn-danger' onclick='deleteSupplier(`". route("supplier.destroy", $supplier->id) ."`)'><i class='fa fa-trash-alt'></i></button>
                </div>
                ";
            })
            ->addColumn("category", function ($supplier) {
                return $supplier->category->name;
            })
            ->rawColumns(["action"])
            ->make(true);
    }

public function store(Request $request)
{
    $request->validate([
        'supplier_name' => 'required|string',
        'company_name'  => 'required|string',
        'phone'         => 'required|string',
        'address'       => 'required|string',
        'category_id'   => 'required|exists:categories,id', // ensure category exists
    ]);

    $supplier = Supplier::create($request->only([
        'supplier_name',
        'company_name',
        'phone',
        'address',
        'category_id',
    ]));

    return response()->json("Add supplier successfully.", 201);
}


    public function show(string $id)
    {
        $supplier = Supplier::findOrFail($id);

        if ($supplier) {
            return response()->json($supplier);
        }
    }

    public function update(Request $request, string $id)
    {
        $supplier = Supplier::findOrFail($id);

        if ($supplier) {
            $supplier->supplier_name = $request->name;
            $supplier->company_name = $request->name;
            $supplier->name = $request->name;
            $supplier->phone = $request->phone;
            $supplier->address = $request->address;
            $supplier->update();

            return response()->json("Update purchase order successfully.");
        }
    }

    public function destroy(string $id)
    {
        $supplier = Supplier::findOrFail($id);

        if ($supplier) {
            $supplier->delete();

            return response()->json("Delete supplier successfully.");
        }
    }
}
