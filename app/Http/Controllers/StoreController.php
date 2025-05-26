<?php

namespace App\Http\Controllers;

use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StoreController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    public function index()
    {
        $user = Auth::user();
        $store = $user->store;

        if (!$store) {
            return response()->json(['message' => 'You have no store yet'], 404);
        }

        return response()->json($store);
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        if ($user->store) {
            return response()->json(['message' => 'You already have a store'], 400);
        }

        $request->validate([
            'name' => 'required|string|max:255|unique:stores,name',
            'description' => 'nullable|string|max:1000',
        ]);

        $store = Store::create([
            'name' => $request->name,
            'description' => $request->description,
            'user_id' => $user->id,
        ]);

        return response()->json([
            'message' => 'Store created successfully',
            'data' => $store
        ], 201);
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $store = $user->store;

        if (!$store) {
            return response()->json(['message' => 'You have no store yet'], 404);
        }

        $request->validate([
            'name' => 'sometimes|string|max:255|unique:stores,name,' . $store->id,
            'description' => 'nullable|string|max:1000',
        ]);

        $store->update($request->only(['name', 'description']));

        return response()->json([
            'message' => 'Store updated successfully',
            'data' => $store
        ]);
    }

    public function destroy()
    {
        $user = Auth::user();
        $store = $user->store;

        if (!$store) {
            return response()->json(['message' => 'You have no store to delete'], 404);
        }

        // optional: cek apakah ada produk, kategori, dll sebelum hapus
        $store->delete();

        return response()->json(['message' => 'Store deleted successfully']);
    }
}
