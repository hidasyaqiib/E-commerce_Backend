<?php

namespace App\Http\Controllers;

use App\Models\Store;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    // Buat store baru untuk admin
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        // Cek apakah admin sudah punya toko
        if (Store::where('admin_id', auth()->id())->exists()) {
            return response()->json([
                'message' => 'You already have a store',
            ], 400);
        }

        $store = Store::create([
            'name' => $request->name,
            'description' => $request->description,
            'admin_id' => auth()->id(),
        ]);

        return response()->json([
            'message' => 'Store created successfully',
            'data' => $store,
        ], 201);
    }

    // Ambil data store milik admin yang login
    public function myStore()
    {
        $store = Store::where('admin_id', auth()->id())->first();

        if (!$store) {
            return response()->json([
                'message' => 'You have no store yet',
            ], 404);
        }

        return response()->json([
            'message' => 'Store retrieved successfully',
            'data' => $store,
        ]);
    }

    // Ambil store berdasarkan ID
    public function show($id)
    {
        $store = Store::find($id);

        if (!$store) {
            return response()->json([
                'message' => 'Store not found',
            ], 404);
        }

        return response()->json([
            'message' => 'Store retrieved successfully',
            'data' => $store,
        ]);
    }

    // Update store milik admin
    public function update(Request $request)
    {
        $store = Store::where('admin_id', auth()->id())->first();

        if (!$store) {
            return response()->json([
                'message' => 'You have no store to update',
            ], 404);
        }

        $request->validate([
            'name' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
        ]);

        $store->update($request->only(['name', 'description']));

        return response()->json([
            'message' => 'Store updated successfully',
            'data' => $store,
        ]);
    }

    // Hapus store milik admin
    public function destroy()
    {
        $store = Store::where('admin_id', auth()->id())->first();

        if (!$store) {
            return response()->json([
                'message' => 'You have no store to delete',
            ], 404);
        }

        $store->delete();

        return response()->json([
            'message' => 'Store deleted successfully',
        ]);
    }
}
