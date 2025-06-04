<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\StoreController;

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
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name"},
     *             @OA\Property(property="name", type="string", maxLength=255, example="My Store"),
     *             @OA\Property(property="description", type="string", example="This is my new store")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Store created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Store created successfully"),
     *             @OA\Property(property="data", type="object")
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
        // method code
    }

    /**
     * @OA\Get(
     *     path="/api/stores/my-store",
     *     summary="Get the store owned by the authenticated admin",
     *     tags={"Stores"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Store retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Store retrieved successfully"),
     *             @OA\Property(property="data", type="object")
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
        // method code
    }

    /**
     * @OA\Get(
     *     path="/api/stores/{id}",
     *     summary="Get store by ID",
     *     tags={"Stores"},
     *     security={{"bearerAuth":{}}},
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
     *             @OA\Property(property="data", type="object")
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
        // method code
    }

    /**
     * @OA\Put(
     *     path="/api/stores",
     *     summary="Update the store owned by the authenticated admin",
     *     tags={"Stores"},
     *     security={{"bearerAuth":{}}},
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
     *             @OA\Property(property="data", type="object")
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
        // method code
    }

    /**
     * @OA\Delete(
     *     path="/api/stores",
     *     summary="Delete the store owned by the authenticated admin",
     *     tags={"Stores"},
     *     security={{"bearerAuth":{}}},
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
        // method code
    }
}
