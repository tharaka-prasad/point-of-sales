<?php
namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(){
        $menu = "Product";
        $categories = Category::select("id", "name")->get();

        return view("product.index", compact("menu", "categories"));
    }

    public function autoCode()
    {
        $lastProduct = Product::latest()->first();
        $lastCode    = $lastProduct ? $lastProduct->code : '';
        $newNumber   = $lastCode ? intval(substr($lastCode, 1)) + 1 : 1;
        $code        = 'P' . code_generator($newNumber, 5); // e.g., P00001

        return response()->json(['code' => $code]);
    }

    // Store product
    public function store(Request $request)
    {
        // ✅ Validate input
        $validated = $request->validate([
            'code'        => 'nullable|string|max:50|unique:products,code',
            'name'        => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'brand'       => 'nullable|string|max:255',
            'price'       => 'required|numeric|min:1',
            'sell_price'  => 'required|numeric|min:1|gte:price',
            'discount'    => 'nullable|numeric|min:0',
            'stock'       => 'required|integer|min:1',
            'expiry_date' => 'required|date',
            'batch_no'    => 'nullable|string|max:255',
        ], [
            // ✅ Custom messages (optional)
            'name.required'        => 'Product name is required.',
            'category_id.required' => 'Please select a category.',
            'brand.required'       => 'Product brand is required.',
            'price.required'       => 'Purchase price is required.',
            'sell_price.gte'       => 'Sell price must be greater than or equal to purchase price.',
            'stock.min'            => 'Stock must be at least 1.',
        ]);

        // ✅ Generate auto code if not provided
        if (empty($validated['code'])) {
            $lastProduct       = Product::latest()->first();
            $lastCode          = $lastProduct ? $lastProduct->code : '';
            $newNumber         = $lastCode ? intval(substr($lastCode, 1)) + 1 : 1;
            $validated['code'] = 'P' . code_generator($newNumber, 5);
        }

        // ✅ Create product
        $product = Product::create($validated);

        // ✅ Return JSON success response
        return response()->json([
            'message' => 'Product added successfully.',
            'product' => $product,
        ], 201);
    }

    // Example DataTable method
    public function data()
    {
        $products = Product::with('category')->latest()->get();

        return datatables()
            ->of($products)
            ->addIndexColumn()
            ->addColumn("select_all_product", function ($product) {
                return "<input type='checkbox' name='product_id[]' value='" . $product->id . "'>";
            })
            ->addColumn("code", function ($product) {
                return "<span class='badge badge-success' style='font-size: 14px;'>" . $product->code . "</span>";
            })
            ->addColumn("price", function ($product) {
                return lkr_money_format($product->price);
            })
            ->addColumn("sell_price", function ($product) {
                return lkr_money_format($product->sell_price);
            })
            ->addColumn('category', function ($product) {
                return $product->category ? $product->category->name : '';
            })
            ->addColumn("action", function ($product) {
                return "
                <div class='btn-group'>
                    <button type='button' class='btn btn-xs btn-warning mr-3' onclick='editProduct(`" . route("product.update", $product->id) . "`)'><i class='fa fa-pencil-alt'></i></button>
                    <button type='button' class='btn btn-xs btn-danger' onclick='deleteProduct(`" . route("product.destroy", $product->id) . "`)'><i class='fa fa-trash-alt'></i></button>
                </div>
                ";
            })
            ->rawColumns(["select_all_product", "code", "action"])
            ->make(true);
    }

    public function show(string $id)
    {
        $product                = Product::with('category')->findOrFail($id);
        $product->category_id   = $product->category->id;
        $product->category_name = $product->category->name;

        if ($product) {
            return response()->json($product);
        }
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        // ✅ Validate input
        $validated = $request->validate([
            'code'        => 'nullable|string|max:50|unique:products,code,' . $product->id,
            'name'        => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'brand'       => 'nullable|string|max:255',
            'price'       => 'required|numeric|min:1',
            'sell_price'  => 'required|numeric|min:1|gte:price',
            'discount'    => 'nullable|numeric|min:0',
            'stock'       => 'required|integer|min:1',
            'expiry_date' => 'required|date',
            'batch_no'    => 'nullable|string|max:255'
        ], [
            // ✅ Custom messages (optional)
            'name.required'        => 'Product name is required.',
            'category_id.required' => 'Please select a category.',
            'brand.required'       => 'Product brand is required.',
            'price.required'       => 'Purchase price is required.',
            'sell_price.gte'       => 'Sell price must be greater than or equal to purchase price.',
            'stock.min'            => 'Stock must be at least 1.',
        ]);

        // ✅ Generate auto code if empty
        if (empty($validated['code'])) {
            $lastProduct       = Product::latest()->first();
            $lastCode          = $lastProduct ? $lastProduct->code : '';
            $newNumber         = $lastCode ? intval(substr($lastCode, 1)) + 1 : 1;
            $validated['code'] = 'P' . code_generator($newNumber, 5);
        }

        // ✅ Update product
        $product->update($validated);

        // ✅ Return JSON success response
        return response()->json([
            'message' => 'Product updated successfully.',
            'product' => $product,
        ], 200);
    }

    public function destroy(string $id)
    {
        $product = Product::findOrFail($id);

        if ($product) {
            $product->delete();

            return response()->json("Delete product successfully.");
        }
    }

    public function deleteSelected(Request $request)
    {
        if ($request->has('product_id') && is_array($request->product_id)) {
            foreach ($request->product_id as $product_id) {
                $product = Product::findOrFail($product_id);

                if ($product) {
                    $product->delete();
                }
            }

            return response()->json(['message' => 'Selected products deleted successfully.']);
        }

        return response()->json(['error' => 'No products selected.'], 400);
    }

    public function printBarcode(Request $request)
    {
        if ($request->has('product_id') && is_array($request->product_id)) {
            $data_product    = [];
            $data_product_id = $request->product_id;

            foreach ($data_product_id as $product_id) {
                $product        = Product::findOrFail($product_id);
                $data_product[] = $product;
            }

            $pdf = Pdf::loadView('product.barcode', compact('data_product'));
            $pdf->setPaper("a4", "portrait");

            return $pdf->stream('product.pdf');
        }

        return response()->json(['error' => 'No products selected.'], 400);
    }
}
