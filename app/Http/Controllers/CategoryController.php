<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Facades\Auth; // Tambahkan di atas

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum'); // proteksi semua method harus admin login
    }

    public function index()
    {
        $categories = Category::all();
        return response()->json($categories);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
        ]);

        $category = Category::create([
            'name' => $request->name,
            'admin_id' => Auth::id(), // otomatis ambil id admin yang login
        ]);

        return response()->json([
            'message' => 'Category created successfully',
            'data' => $category
        ], 201);
    }

    public function show($id)
    {
        $category = Category::with('products')->find($id);
        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }
        return response()->json($category);
    }

    public function update(Request $request, $id)
    {
        $category = Category::find($id);
        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }

        $request->validate([
            'name' => 'sometimes|string|max:255|unique:categories,name,' . $id,
        ]);

        $category->update([
            'name' => $request->name
        ]);

        return response()->json([
            'message' => 'Category updated successfully',
            'data' => $category
        ]);
    }

    public function destroy($id)
    {
        $category = Category::find($id);
        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }

        if ($category->products()->count() > 0) {
            return response()->json(['message' => 'Category cannot be deleted, it has associated products'], 400);
        }

        $category->delete();
        return response()->json(['message' => 'Category deleted successfully']);
    }
}

