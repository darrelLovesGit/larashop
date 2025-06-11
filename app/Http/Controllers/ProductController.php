<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\History;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Response;

class ProductController extends Controller
{
    public function index(Request $request) {
        
        $keyword = $request->input('keyword');
        $sort = $request->input('sort') ?? 'asc';

        $products = new Product();


        if ($keyword) {
            $products = $products->where('name', 'like', "%{$keyword}%");
        }
        $products = $products->orderBy('id', $sort)->paginate(6);

        return response()->json(['products' => $products]);

    }
    public function store(Request $request) {
        try{
        $validatedData = $request->validate([
            'name' => 'required',
            'price' => 'required|numeric',
            'quantity' => 'required|integer',
            'brand_id' => 'required|exists:brands,id',
            'category_id' => 'required|exists:categories,id',
        ]);
        } catch(ValidationException $e) {
            return response()->json(['message' => 'Validation failed', 'errors' => $e->errors()]);
        }

        $product = Product::create($request->all());
        
        History::create(["user_id" => Auth::user()->id, "product_id" => $product->id, "type" => "created"]);

        return response()->json(['product' => $product]);

    }

    public function show(string $id) {
        $product = Product::with("histories")->find($id);

        

        if ($product) {
            return response()->json(['product' => $product]);
        } else {
            return response()->json(['message' => 'Produk tidak ditemukan'], 404);
;        }

    }
    public function update(Request $request, string $id) {
        try{
        $validatedData = $request->validate([
            'name' => 'required',
            'price' => 'required|numeric',
            'quantity' => 'required|integer',
            'brand_id' => 'required|exists:brands,id',
            'category_id' => 'required|exists:categories,id',
        ]);
        } catch(ValidationException $e) {
            return response()->json(['message' => 'Validation failed', 'errors' => $e->errors()]);
        }
        
        $product = Product::find($id);
        
        if (!$product) {
            return response()->json(['message' => 'Produk tidak ditemukan'], 404);

        }
        $product->update($validatedData);
        History::create(["user_id" => Auth::user()->id, "product_id" => $product->id, "type" => "updated"]);

        return response()->json(['message' => 'Produk berhasil diupdate', 'product' => $product]);
    }
    public function destroy(string $id) {
        $product = Product::find($id);

        if (!$product) {
            return response()->json(['message' => 'Produk tidak ditemukan'], 404);

        }
        $product->delete();
        return response()->json(['message' => 'Produk berhasil dihapus']);

    }
}
