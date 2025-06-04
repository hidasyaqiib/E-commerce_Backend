<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class CategorySwaggerController extends Controller
{
    /**
     * @OA\Get(
     *     path="/categories",
     *     tags={"Category"},
     *     operationId="listCategories",
     *     summary="List all categories for the authenticated user's store",
     *     description="Retrieve all product categories for the current user's store",
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="id", type="integer"),
     *                 @OA\Property(property="name", type="string"),
     *                 @OA\Property(property="store_id", type="integer"),
     *                 @OA\Property(property="admin_id", type="integer")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="You must create a store first")
     *         )
     *     )
     * )
     */
    public function index()
    {
        // Implementation
    }

    /**
     * @OA\Post(
     *     path="/categories",
     *     tags={"Category"},
     *     operationId="storeCategory",
     *     summary="Create a new category for the authenticated user's store",
     *     description="Create a new product category in the current user's store",
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name"},
     *             @OA\Property(property="name", type="string", example="Electronics")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Category created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Category created successfully"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer"),
     *                 @OA\Property(property="name", type="string"),
     *                 @OA\Property(property="store_id", type="integer"),
     *                 @OA\Property(property="admin_id", type="integer")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="You must create a store first")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Category name already exists in your store")
     *         )
     *     )
     * )
     */
    public function store(Request $request)
    {
        // Implementation
    }

    /**
     * @OA\Get(
     *     path="/categories/{id}",
     *     tags={"Category"},
     *     operationId="showCategory",
     *     summary="Get a category by ID",
     *     description="Retrieve a specific category with its products (must belong to the user's store)",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the category to retrieve",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer"),
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="store_id", type="integer"),
     *             @OA\Property(property="admin_id", type="integer"),
     *             @OA\Property(property="products", type="array", @OA\Items(type="object"))
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthorized access to this category")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not Found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Category not found")
     *         )
     *     )
     * )
     */
    public function show($id)
    {
        // Implementation
    }

    /**
     * @OA\Put(
     *     path="/categories/{id}",
     *     tags={"Category"},
     *     operationId="updateCategory",
     *     summary="Update a category",
     *     description="Update an existing category (must belong to the user's store)",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the category to update",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name"},
     *             @OA\Property(property="name", type="string", example="Updated Electronics")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Category updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Category updated successfully"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer"),
     *                 @OA\Property(property="name", type="string"),
     *                 @OA\Property(property="store_id", type="integer"),
     *                 @OA\Property(property="admin_id", type="integer")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthorized")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not Found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Category not found")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The name has already been taken")
     *         )
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        // Implementation
    }

    /**
     * @OA\Delete(
     *     path="/categories/{id}",
     *     tags={"Category"},
     *     operationId="deleteCategory",
     *     summary="Delete a category",
     *     description="Delete a category if it has no associated products (must belong to the user's store)",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the category to delete",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Category deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Category deleted successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Category cannot be deleted, it has associated products")
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthorized")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not Found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Category not found")
     *         )
     *     )
     * )
     */
    public function destroy($id)
    {
        // Implementation
    }
}