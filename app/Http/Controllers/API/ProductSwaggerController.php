<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductSwaggerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum')->except(['index', 'show']);
    }

    /**
     * @OA\Get(
     *     path="/products",
     *     tags={"Product"},
     *     operationId="listProduct",
     *     summary="List Products",
     *     description="Retrieve a list of products",
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             example={
     *                 {"id":1,"name":"Product A","price":10000,"stock":50,"category_id":1},
     *                 {"id":2,"name":"Product B","price":20000,"stock":30,"category_id":2}
     *             }
     *         )
     *     )
     * )
     */
    public function index()
    {
        $products = Product::with('category')->get();
        return response()->json($products);
    }

    /**
     * @OA\Post(
     *     path="/products",
     *     tags={"Product"},
     *     operationId="createProduct",
     *     summary="Create a new Product",
     *     description="Create a new product record",
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name","price","stock","category_id"},
     *             @OA\Property(property="name", type="string", example="New Product"),
     *             @OA\Property(property="price", type="number", example=15000),
     *             @OA\Property(property="stock", type="integer", example=100),
     *             @OA\Property(property="category_id", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Product created successfully",
     *         @OA\JsonContent(
     *             example={
     *                 "message": "Product created successfully",
     *                 "data": {
     *                     "id": 3,
     *                     "name": "New Product",
     *                     "price": 15000,
     *                     "stock": 100,
     *                     "category_id": 1
     *                 }
     *             }
     *         )
     *     )
     * )
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
        ]);

        $product = Product::create([
            'name' => $request->name,
            'price' => $request->price,
            'stock' => $request->stock,
            'category_id' => $request->category_id,
        ]);

        return response()->json([
            'message' => 'Product created successfully',
            'data' => $product
        ], 201);
    }

    /**
     * @OA\Get(
     *     path="/products/{id}",
     *     tags={"Product"},
     *     operationId="getProduct",
     *     summary="Get a Product by ID",
     *     description="Retrieve a single product by its ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of product",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             example={
     *                 "id":1,
     *                 "name":"Product A",
     *                 "price":10000,
     *                 "stock":50,
     *                 "category_id":1
     *             }
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Product not found"
     *     )
     * )
     */
    public function show($id)
    {
        $product = Product::with('category')->find($id);

        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        return response()->json($product);
    }

    /**
     * @OA\Put(
     *     path="/products/{id}",
     *     tags={"Product"},
     *     operationId="updateProduct",
     *     summary="Update a Product",
     *     description="Update an existing product",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of product to update",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=false,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="Updated Product"),
     *             @OA\Property(property="price", type="number", example=17000),
     *             @OA\Property(property="stock", type="integer", example=60),
     *             @OA\Property(property="category_id", type="integer", example=2)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Product updated successfully",
     *         @OA\JsonContent(
     *             example={
     *                 "message": "Product updated successfully",
     *                 "data": {
     *                     "id":1,
     *                     "name":"Updated Product",
     *                     "price":17000,
     *                     "stock":60,
     *                     "category_id":2
     *                 }
     *             }
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Product not found"
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        $product = Product::find($id);
        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        $request->validate([
            'category_id' => 'sometimes|exists:categories,id',
            'name' => 'sometimes|string|max:255',
            'price' => 'sometimes|numeric|min:0',
            'stock' => 'sometimes|integer|min:0',
        ]);

        $product->update($request->only(['name', 'price', 'stock', 'category_id']));

        return response()->json([
            'message' => 'Product updated successfully',
            'data' => $product
        ]);
    }

    /**
     * @OA\Delete(
     *     path="/products/{id}",
     *     tags={"Product"},
     *     operationId="deleteProduct",
     *     summary="Delete a Product",
     *     description="Delete a product by ID",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of product to delete",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Product deleted successfully",
     *         @OA\JsonContent(
     *             example={
     *                 "message": "Product deleted successfully"
     *             }
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Product not found"
     *     )
     * )
     */
    public function destroy($id)
    {
        $product = Product::find($id);
        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        $product->delete();

        return response()->json(['message' => 'Product deleted successfully']);
    }
}
