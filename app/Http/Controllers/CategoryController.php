<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum'); // semua endpoint wajib login
    }

    public function index()
    {
        $user = Auth::user();
        $store = $user->store;

        if (!$store) {
            return response()->json(['message' => 'You must create a store first'], 403);
        }

        $categories = Category::where('store_id', $store->id)->get();
        return response()->json($categories);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $user = Auth::user();
        $store = $user->store;

        if (!$store) {
            return response()->json(['message' => 'You must create a store first'], 403);
        }

        if (Category::where('store_id', $store->id)->where('name', $request->name)->exists()) {
            return response()->json(['message' => 'Category name already exists in your store'], 422);
        }

        $category = Category::create([
            'name' => $request->name,
            'store_id' => $store->id,
            'admin_id' => $user->id, // otomatis ambil dari user login
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

        $user = Auth::user();
        $store = $user->store;

        if (!$store || $category->store_id !== $store->id) {
            return response()->json(['message' => 'Unauthorized access to this category'], 403);
        }

        return response()->json($category);
    }

    public function update(Request $request, $id)
    {
        $category = Category::find($id);

        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }

        $user = Auth::user();
        $store = $user->store;

        if (!$store || $category->store_id !== $store->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id . ',id,store_id,' . $store->id,
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

        $user = Auth::user();
        $store = $user->store;

        if (!$store || $category->store_id !== $store->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if ($category->products()->count() > 0) {
            return response()->json(['message' => 'Category cannot be deleted, it has associated products'], 400);
        }

        $category->delete();
        return response()->json(['message' => 'Category deleted successfully']);
    }
}
