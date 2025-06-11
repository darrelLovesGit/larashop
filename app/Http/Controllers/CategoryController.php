<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Response;

class CategoryController extends Controller
{
    public function index(Request $request) {
        
        $keyword = $request->input('keyword');
        $sort = $request->input('sort') ?? 'asc';
        $categories = new Category();

        if ($keyword) {
            $categories = $categories->where('category', 'like', "%{$keyword}%");
        }
        $categories = $categories->orderBy('id', $sort)->paginate(6);
        return response()->json(['categories' => $categories]);

    }
    public function store(Request $request) {
        try {
        $validatedData = $request->validate([
            'category' => 'required|string',
        ]);
        } catch(ValidationException $e) {
            return response()->json(['message' => 'Validation failed', 'errors' => $e->errors()]);
        }

        $category = Category::create($request->all());
        return response()->json(['category' => $category]);
    }
    public function show(string $id) {
        $category = Category::find($id);


        if ($category) {
            return response()->json(['category' => $category]);
        } else {
            return response()->json(['message' => 'Kategori tidak ditemukan'], 404);
;        }

    }
    public function update(Request $request, string $id) {
        try {

        $validatedData = $request->validate([
            'category' => 'required|string',
        ]);
        } catch(ValidationException $e) {
            return response()->json(['message' => 'Validation failed', 'errors' => $e->errors()]);
        }
        $category = Category::find($id);
        if (!$category) {
            return response()->json(['message' => 'Kategori tidak ditemukan'], 404);

        }
        $category->update($validatedData);
        return response()->json(['message' => 'Kategori berhasil diupdate', 'category' => $category]);
    }
    public function destroy(string $id) {
        $category = Category::find($id);

        if (!$category) {
            return response()->json(['message' => 'Kategori tidak ditemukan'], 404);

        }
        $category->delete();
        return response()->json(['message' => 'Kategori berhasil dihapus']);

    }
}
