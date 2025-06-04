<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * @OA\Tag(
 *     name="Products",
 *     description="API untuk manajemen produk"
 * )
 */
class ProductSwaggerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin')->except(['index', 'show', 'getByCategory', 'getByStore']);
    }

    protected function getAuthenticatedAdmin()
    {
        return Auth::guard('admin')->user();
    }

    /**
     * @OA\Post(
     *     path="/api/products",
     *     summary="Create a new product",
     *     tags={"Products"},
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name","price","stock","category_id"},
     *             @OA\Property(property="name", type="string", example="Laptop XYZ"),
     *             @OA\Property(property="price", type="number", example=2500000),
     *             @OA\Property(property="stock", type="integer", example=10),
     *             @OA\Property(property="category_id", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(response=201, description="Product created successfully"),
     *     @OA\Response(response=403, description="You must create a store first"),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function store() {}

    /**
     * @OA\Get(
     *     path="/api/products",
     *     summary="Get all products",
     *     tags={"Products"},
     *     @OA\Response(response=200, description="List of products")
     * )
     */
    public function index() {}

    /**
     * @OA\Get(
     *     path="/api/products/{id}",
     *     summary="Get product by ID",
     *     tags={"Products"},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Product found"),
     *     @OA\Response(response=404, description="Product not found")
     * )
     */
    public function show() {}

    /**
     * @OA\Get(
     *     path="/api/products/category/{categoryId}",
     *     summary="Get products by category",
     *     tags={"Products"},
     *     @OA\Parameter(name="categoryId", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Products found"),
     *     @OA\Response(response=404, description="No products found")
     * )
     */
    public function getByCategory() {}

    /**
     * @OA\Get(
     *     path="/api/products/store/{storeId}",
     *     summary="Get products by store",
     *     tags={"Products"},
     *     @OA\Parameter(name="storeId", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Products found"),
     *     @OA\Response(response=404, description="No products found")
     * )
     */
    public function getByStore() {}

    /**
     * @OA\Put(
     *     path="/api/products/{id}",
     *     summary="Update product by ID",
     *     tags={"Products"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="Laptop Updated"),
     *             @OA\Property(property="price", type="number", example=2700000),
     *             @OA\Property(property="stock", type="integer", example=12),
     *             @OA\Property(property="category_id", type="integer", example=2)
     *         )
     *     ),
     *     @OA\Response(response=200, description="Product updated successfully"),
     *     @OA\Response(response=403, description="Unauthorized"),
     *     @OA\Response(response=404, description="Product not found")
     * )
     */
    public function update() {}

    /**
     * @OA\Delete(
     *     path="/api/products/{id}",
     *     summary="Delete product by ID",
     *     tags={"Products"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Product deleted successfully"),
     *     @OA\Response(response=403, description="Unauthorized"),
     *     @OA\Response(response=404, description="Product not found")
     * )
     */
    public function destroy() {}
}
