<?php
namespace App\Http\Controllers;

use App\Models\Supplier;
use App\Models\Category;
use Illuminate\Http\Request;


class SupplierController extends Controller
{
    // Show supplier list page
    public function index(){
        $menu = 'Supplier';
        $suppliers = Supplier::all();
        $categories = Category::all();

        return view('supplier.index', compact('menu','suppliers','categories'));
    }



    public function data()
    {
        $suppliers = Supplier::with('category')->latest(); // keep the relationship + ordering

        return datatables()
            ->of($suppliers)
            ->addIndexColumn()
            ->addColumn('category_name', function ($supplier) {
                return $supplier->category ? $supplier->category->category_name : '-';
            })
            ->addColumn('action', function ($supplier) {
                return "
                    <div class='btn-group'>
                        <button class='btn btn-xs btn-warning mr-3' onclick='editSupplier(`" . route("supplier.update", $supplier->id) . "`)'>
                            <i class='fa fa-pencil-alt'></i>
                        </button>
                        <button class='btn btn-xs btn-danger' onclick='deleteSupplier(`" . route("supplier.destroy", $supplier->id) . "`)'>
                            <i class='fa fa-trash-alt'></i>
                        </button>
                    </div>
                ";
            })
            ->rawColumns(['action'])
            ->make(true);
    }


    // Store new supplier
    public function store(Request $request)
    {
        $supplier = Supplier::create($request->all());

        if ($supplier) {
            return response()->json("Supplier added successfully.", 201);
        }

        return response()->json("Failed to add supplier.", 500);
    }

    // Show single supplier for edit
    public function show(string $id)
    {
        $supplier = Supplier::findOrFail($id);
        return response()->json($supplier);
    }

    // Update supplier
    public function update(Request $request, string $id)
    {
        $supplier = Supplier::findOrFail($id);

        $supplier->update([
            'supplier_name' => $request->supplier_name,
            'company_name'  => $request->company_name,
            'category'      => $request->name,
            'phone'         => $request->phone,
            'address'       => $request->address,
        ]);

        return response()->json("Supplier updated successfully.");
    }

    // Delete supplier
    public function destroy(string $id)
    {
        $supplier = Supplier::findOrFail($id);
        $supplier->delete();

        return response()->json("Supplier deleted successfully.");
    }

    public function edit($id){
        $supplier = Supplier::with('category')->findOrFail($id);
        return response()->json($supplier);
    }

    public function create(){
        $categories = Category::all();
        return view('supplier.form', compact('categories'));
    }
}
