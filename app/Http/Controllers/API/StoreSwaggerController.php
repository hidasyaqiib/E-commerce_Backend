<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Store;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

/**
 * @OA\Schema(
 *     schema="Store",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="My Store"),
 *     @OA\Property(property="description", type="string", example="My toko description"),
 *     @OA\Property(property="admin_id", type="integer", example=5),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 */
class StoreSwaggerController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/stores",
     *     summary="Create a new store for admin",
     *     tags={"Stores"},
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name"},
     *             @OA\Property(property="name", type="string", maxLength=255, example="My Store"),
     *             @OA\Property(property="description", type="string", example="This is my new store"),
     *             @OA\Property(property="admin_id", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Store created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Store created successfully"),
     *             @OA\Property(property="data", ref="#/components/schemas/Store")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Store already exists",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="You already have a store")
     *         )
     *     )
     * )
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'admin_id' => 'required|integer|exists:admins,id',
        ]);

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

    /**
     * @OA\Get(
     *     path="/api/stores/my-store",
     *     summary="Get the store owned by the authenticated admin",
     *     tags={"Stores"},
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Store retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Store retrieved successfully"),
     *             @OA\Property(property="data", ref="#/components/schemas/Store")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Store not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="You have no store yet")
     *         )
     *     )
     * )
     */
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

    /**
     * @OA\Get(
     *     path="/api/stores/{id}",
     *     summary="Get store by ID",
     *     tags={"Stores"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Store ID",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Store retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Store retrieved successfully"),
     *             @OA\Property(property="data", ref="#/components/schemas/Store")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Store not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Store not found")
     *         )
     *     )
     * )
     */
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

    /**
     * @OA\Put(
     *     path="/api/stores",
     *     summary="Update the store owned by the authenticated admin",
     *     tags={"Stores"},
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=false,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", maxLength=255, example="Updated Store Name"),
     *             @OA\Property(property="description", type="string", example="Updated description")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Store updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Store updated successfully"),
     *             @OA\Property(property="data", ref="#/components/schemas/Store")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Store not found to update",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="You have no store to update")
     *         )
     *     )
     * )
     */
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

    /**
     * @OA\Delete(
     *     path="/api/stores",
     *     summary="Delete the store owned by the authenticated admin",
     *     tags={"Stores"},
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Store deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Store deleted successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Store not found to delete",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="You have no store to delete")
     *         )
     *     )
     * )
     */
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

    /**
     * @OA\Get(
     *     path="/api/stores",
     *     summary="Get all stores",
     *     tags={"Stores"},
     *     @OA\Response(
     *         response=200,
     *         description="Stores retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Stores retrieved successfully"),
     *             @OA\Property(property="data", type="array",
     *                 @OA\Items(ref="#/components/schemas/Store")
     *             ),
     *             @OA\Property(property="total", type="integer", example=10)
     *         )
     *     )
     * )
     */
    public function index()
    {
        $stores = Store::all();

        return response()->json([
            'message' => 'Stores retrieved successfully',
            'data' => $stores,
            'total' => $stores->count(),
        ]);
    }
}
