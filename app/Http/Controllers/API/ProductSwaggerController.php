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
        $this->middleware('auth:admin')->except(['index', 'show', 'getByCategory', 'getByStore']);
    }

    /**
     * @OA\Get(
     *     path="/products",
     *     tags={"Product"},
     *     summary="Get all products",
     *     operationId="getProducts",
     *     description="Returns list of products with category and store",
     *     @OA\Response(
     *         response=200,
     *         description="List of products",
     *         @OA\JsonContent(type="array", @OA\Items(
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="name", type="string", example="Product A"),
     *             @OA\Property(property="price", type="number", example=10000),
     *             @OA\Property(property="stock", type="integer", example=50),
     *             @OA\Property(property="category_id", type="integer", example=1),
     *             @OA\Property(property="store_id", type="integer", example=1),
     *         ))
     *     )
     * )
     */
    public function index() {}

    /**
     * @OA\Post(
     *     path="/products",
     *     tags={"Product"},
     *     summary="Create new product",
     *     operationId="createProduct",
     *     security={{"adminAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "price", "stock", "category_id"},
     *             @OA\Property(property="name", type="string", example="New Product"),
     *             @OA\Property(property="price", type="number", example=12000),
     *             @OA\Property(property="stock", type="integer", example=25),
     *             @OA\Property(property="category_id", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Product created",
     *         @OA\JsonContent(
     *             example={
     *                 "message": "Product created successfully",
     *                 "data": {
     *                     "id": 5,
     *                     "name": "New Product",
     *                     "price": 12000,
     *                     "stock": 25,
     *                     "category_id": 1,
     *                     "store_id": 2
     *                 }
     *             }
     *         )
     *     ),
     *     @OA\Response(response=403, description="You must create a store first")
     * )
     */
    public function store(Request $request) {}

    /**
     * @OA\Get(
     *     path="/products/{id}",
     *     tags={"Product"},
     *     summary="Get product by ID",
     *     operationId="getProductById",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of product",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Product found",
     *         @OA\JsonContent(
     *             example={
     *                 "id": 2,
     *                 "name": "Product B",
     *                 "price": 15000,
     *                 "stock": 10,
     *                 "category_id": 2,
     *                 "store_id": 1
     *             }
     *         )
     *     ),
     *     @OA\Response(response=404, description="Product not found")
     * )
     */
    public function show($id) {}

    /**
     * @OA\Put(
     *     path="/products/{id}",
     *     tags={"Product"},
     *     summary="Update product",
     *     operationId="updateProduct",
     *     security={{"adminAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Product ID to update",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=false,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="Updated Product"),
     *             @OA\Property(property="price", type="number", example=20000),
     *             @OA\Property(property="stock", type="integer", example=80),
     *             @OA\Property(property="category_id", type="integer", example=3)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Product updated",
     *         @OA\JsonContent(
     *             example={
     *                 "message": "Product updated successfully",
     *                 "data": {
     *                     "id": 2,
     *                     "name": "Updated Product",
     *                     "price": 20000,
     *                     "stock": 80,
     *                     "category_id": 3,
     *                     "store_id": 1
     *                 }
     *             }
     *         )
     *     ),
     *     @OA\Response(response=403, description="Unauthorized"),
     *     @OA\Response(response=404, description="Product not found")
     * )
     */
    public function update(Request $request, $id) {}

    /**
     * @OA\Delete(
     *     path="/products/{id}",
     *     tags={"Product"},
     *     summary="Delete product",
     *     operationId="deleteProduct",
     *     security={{"adminAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Product ID to delete",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Product deleted",
     *         @OA\JsonContent(example={"message": "Product deleted successfully"})
     *     ),
     *     @OA\Response(response=403, description="Unauthorized"),
     *     @OA\Response(response=404, description="Product not found")
     * )
     */
    public function destroy($id) {}

    /**
     * @OA\Get(
     *     path="/products/category/{categoryId}",
     *     tags={"Product"},
     *     summary="Get products by category",
     *     operationId="getProductsByCategory",
     *     @OA\Parameter(
     *         name="categoryId",
     *         in="path",
     *         required=true,
     *         description="Category ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Products found",
     *         @OA\JsonContent(example={
     *             "category_id": 1,
     *             "products": {
     *                 {"id":1,"name":"Product A","price":10000,"stock":50,"category_id":1,"store_id":1}
     *             }
     *         })
     *     ),
     *     @OA\Response(response=404, description="No products found for this category")
     * )
     */
    public function getByCategory($categoryId) {}

    /**
     * @OA\Get(
     *     path="/products/store/{storeId}",
     *     tags={"Product"},
     *     summary="Get products by store",
     *     operationId="getProductsByStore",
     *     @OA\Parameter(
     *         name="storeId",
     *         in="path",
     *         required=true,
     *         description="Store ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Products found",
     *         @OA\JsonContent(example={
     *             "store_id": 1,
     *             "products": {
     *                 {"id":1,"name":"Product A","price":10000,"stock":50,"category_id":1,"store_id":1}
     *             }
     *         })
     *     ),
     *     @OA\Response(response=404, description="No products found for this store")
     * )
     */
    public function getByStore($storeId) {}
}
