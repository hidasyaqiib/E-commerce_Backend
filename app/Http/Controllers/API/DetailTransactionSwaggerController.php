<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DetailTransaction;
use App\Models\Transaction;

class DetailTransactionSwaggerController extends Controller
{
    /**
     * @OA\Get(
     *     path="/detail-transactions",
     *     tags={"DetailTransaction"},
     *     operationId="listDetailTransactions",
     *     summary="List all detail transactions",
     *     description="Retrieve all detail transactions with their related transaction, customer, and product",
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="transaction_id", type="integer", example=1),
     *                 @OA\Property(property="product_id", type="integer", example=1),
     *                 @OA\Property(property="quantity", type="integer", example=2),
     *                 @OA\Property(property="status", type="string", example="pending"),
     *                 @OA\Property(property="transaction", type="object"),
     *                 @OA\Property(property="product", type="object")
     *             )
     *         )
     *     )
     * )
     */
    public function index() {}

    /**
     * @OA\Get(
     *     path="/detail-transactions/{transaction_id}",
     *     tags={"DetailTransaction"},
     *     operationId="showDetailTransaction",
     *     summary="Show details of a specific transaction",
     *     description="Retrieve detail transactions by transaction ID, including transaction and product data",
     *     @OA\Parameter(
     *         name="transaction_id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="transaction", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="customer_id", type="integer", example=1),
     *                 @OA\Property(property="total_amount", type="integer", example=50000)
     *             ),
     *             @OA\Property(
     *                 property="details",
     *                 type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="product_id", type="integer", example=1),
     *                     @OA\Property(property="quantity", type="integer", example=2),
     *                     @OA\Property(property="status", type="string", example="pending"),
     *                     @OA\Property(property="product", type="object")
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function show($transaction_id) {}

    /**
     * @OA\Post(
     *     path="/detail-transactions",
     *     tags={"DetailTransaction"},
     *     operationId="storeDetailTransaction",
     *     summary="Add a new detail transaction",
     *     description="Admin only: Create a new detail transaction",
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"transaction_id", "product_id", "quantity"},
     *             @OA\Property(property="transaction_id", type="integer", example=1),
     *             @OA\Property(property="product_id", type="integer", example=2),
     *             @OA\Property(property="quantity", type="integer", example=3)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Detail transaction added successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Detail transaction added successfully"),
     *             @OA\Property(property="transaction", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="customer_id", type="integer", example=1),
     *                 @OA\Property(property="total_amount", type="integer", example=50000)
     *             ),
     *             @OA\Property(
     *                 property="details",
     *                 type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="integer", example=5),
     *                     @OA\Property(property="transaction_id", type="integer", example=1),
     *                     @OA\Property(property="product_id", type="integer", example=2),
     *                     @OA\Property(property="quantity", type="integer", example=3),
     *                     @OA\Property(property="status", type="string", example="pending"),
     *                     @OA\Property(property="product", type="object")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Unauthorized"
     *     )
     * )
     */
    public function store(Request $request) {}

    /**
     * @OA\Put(
     *     path="/detail-transactions/{id}",
     *     tags={"DetailTransaction"},
     *     operationId="updateDetailTransaction",
     *     summary="Update a detail transaction",
     *     description="Admin only: Update quantity of a detail transaction",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"quantity"},
     *             @OA\Property(property="quantity", type="integer", example=5)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Detail transaction updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Detail transaction updated successfully"),
     *             @OA\Property(property="detail", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="transaction_id", type="integer", example=1),
     *                 @OA\Property(property="product_id", type="integer", example=1),
     *                 @OA\Property(property="quantity", type="integer", example=5),
     *                 @OA\Property(property="status", type="string", example="pending")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Unauthorized"
     *     )
     * )
     */
    public function update(Request $request, $id) {}

    /**
     * @OA\Delete(
     *     path="/detail-transactions/{id}",
     *     tags={"DetailTransaction"},
     *     operationId="deleteDetailTransaction",
     *     summary="Delete a detail transaction",
     *     description="Admin only: Delete a detail transaction by ID",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Detail transaction deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Detail transaction deleted successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Unauthorized"
     *     )
     * )
     */
    public function destroy($id) {}
}
