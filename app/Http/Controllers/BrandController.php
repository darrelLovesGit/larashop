<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\History;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Response;

class BrandController extends Controller
{
    public function index(Request $request) {
        
        $keyword = $request->input('keyword');
        $sort = $request->input('sort') ?? 'asc';
        $brands = new Brand();

        if ($keyword) {
            $brands = $brands->where('name', 'like', "%{$keyword}%");
        }
        $brands = $brands->orderBy('id', $sort)->paginate(6);

        return response()->json(['brands' => $brands]);

    }
    public function store(Request $request) {
        try {
        $validatedData = $request->validate([
            'name' => 'required|string',
        ]);
        } catch(ValidationException $e) {
            return response()->json(['message' => 'Validation failed', 'errors' => $e->errors()]);
        }

        $brand = Brand::create($request->all());

        History::create(["user_id" => Auth::user()->id, "brand_id" => $brand->id, "type" => "created"]);

        return response()->json(['brand' => $brand]);
    }
    public function show(string $id) {
        $brand = Brand::with("histories")->find($id);

        if ($brand) {
            return response()->json(['brand' => $brand]);
        } else {
            return response()->json(['message' => 'Brand tidak ditemukan'], 404);
;        }

    }
    public function update(Request $request, string $id) {
        try {
        $validatedData = $request->validate([
            'name' => 'required|string',
        ]);
        } catch(ValidationException $e) {
            return response()->json(['message' => 'Validation failed', 'errors' => $e->errors()]);
        }
        $brand = Brand::find($id);
        if (!$brand) {
            return response()->json(['message' => 'Brand tidak ditemukan'], 404);

        }
        $brand->update($validatedData);

        History::create(["user_id" => Auth::user()->id, "brand_id" => $brand->id, "type" => "updated"]);
        
        return response()->json(['message' => 'Brand berhasil diupdate', 'brand' => $brand]);
    }
    public function destroy(string $id) {
        $brand = Brand::find($id);

        if (!$brand) {
            return response()->json(['message' => 'Brand tidak ditemukan'], 404);

        }
        $brand->delete();
        return response()->json(['message' => 'Brand berhasil dihapus']);

    }
}
