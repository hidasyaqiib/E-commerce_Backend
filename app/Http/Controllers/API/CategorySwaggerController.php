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
     *     summary="List all categories",
     *     description="Retrieve all product categories",
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             example={
     *                 {
     *                     "id": 1,
     *                     "name": "Electronics",
     *                     "admin_id": 1
     *                 }
     *             }
     *         )
     *     )
     * )
     */
    public function index()
    {
        $categories = Category::all();
        return response()->json($categories);
    }

    /**
     * @OA\Post(
     *     path="/categories",
     *     tags={"Category"},
     *     operationId="storeCategory",
     *     summary="Create a new category",
     *     description="Admin only: Create a new product category",
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name"},
     *             @OA\Property(property="name", type="string", example="Gadgets")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Category created successfully",
     *         @OA\JsonContent(
     *             example={
     *                 "message": "Category created successfully",
     *                 "data": {
     *                     "id": 2,
     *                     "name": "Gadgets",
     *                     "admin_id": 1
     *                 }
     *             }
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     )
     * )
     */
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

    /**
     * @OA\Get(
     *     path="/categories/{id}",
     *     tags={"Category"},
     *     operationId="showCategory",
     *     summary="Get a category by ID",
     *     description="Retrieve a specific category with its products",
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
     *             example={
     *                 "id": 1,
     *                 "name": "Electronics",
     *                 "products": {
     *                     {
     *                         "id": 1,
     *                         "name": "Smartphone",
     *                         "price": 1000000
     *                     }
     *                 }
     *             }
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Category not found"
     *     )
     * )
     */
    public function show($id)
    {
        $category = Category::with('products')->find($id);
        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }
        return response()->json($category);
    }

    /**
     * @OA\Put(
     *     path="/categories/{id}",
     *     tags={"Category"},
     *     operationId="updateCategory",
     *     summary="Update a category",
     *     description="Admin only: Update an existing category",
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
     *             @OA\Property(property="name", type="string", example="Updated Category Name")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Category updated successfully",
     *         @OA\JsonContent(
     *             example={
     *                 "message": "Category updated successfully",
     *                 "data": {
     *                     "id": 1,
     *                     "name": "Updated Category Name",
     *                     "admin_id": 1
     *                 }
     *             }
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Category not found"
     *     )
     * )
     */
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

    /**
     * @OA\Delete(
     *     path="/categories/{id}",
     *     tags={"Category"},
     *     operationId="deleteCategory",
     *     summary="Delete a category",
     *     description="Admin only: Delete a category if it has no associated products",
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
     *             example={
     *                 "message": "Category deleted successfully"
     *             }
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Category cannot be deleted, it has associated products"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Category not found"
     *     )
     * )
     */
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
