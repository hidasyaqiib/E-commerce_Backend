<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Transaction;
use App\Models\DetailTransaction;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionSwaggerController extends Controller
{
    /**
     * @OA\Get(
     *     path="/transactions",
     *     tags={"Transaction"},
     *     operationId="listTransactions",
     *     summary="List all transactions",
     *     description="Retrieve all transactions with related customers and transaction details",
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             example={
     *                 {
     *                     "id": 1,
     *                     "customer_id": 1,
     *                     "grand_total": 250000,
     *                     "payment_method": "credit_card",
     *                     "status": "pending",
     *                     "details": {
     *                         {
     *                             "product_id": 2,
     *                             "quantity": 1,
     *                             "subtotal": 250000
     *                         }
     *                     }
     *                 }
     *             }
     *         )
     *     )
     * )
     */
    public function index()
    {
        $transactions = Transaction::with('customer', 'details.product')->get();
        return response()->json($transactions);
    }


    /**
     * @OA\Post(
     *     path="/transactions",
     *     tags={"Transaction"},
     *     operationId="storeTransaction",
     *     summary="Create a new transaction",
     *     description="Customer can create a new transaction with multiple products",
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"customer_id", "payment_method", "details"},
     *             @OA\Property(property="customer_id", type="integer", example=1),
     *             @OA\Property(property="payment_method", type="string", example="credit_card"),
     *             @OA\Property(
     *                 property="details",
     *                 type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="product_id", type="integer", example=2),
     *                     @OA\Property(property="quantity", type="integer", example=1)
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Transaction created successfully",
     *         @OA\JsonContent(
     *             example={
     *                 "message": "Transaction created successfully",
     *                 "transaction": {
     *                     "id": 1,
     *                     "customer_id": 1,
     *                     "grand_total": 250000,
     *                     "payment_method": "credit_card",
     *                     "status": "pending",
     *                     "details": {
     *                         {
     *                             "product_id": 2,
     *                             "quantity": 1,
     *                             "subtotal": 250000
     *                         }
     *                     }
     *                 }
     *             }
     *         )
     *     )
     * )
     */
    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'payment_method' => 'required|string',
            'details' => 'required|array',
            'details.*.product_id' => 'required|exists:products,id',
            'details.*.quantity' => 'required|integer|min:1',
        ]);

        $grandTotal = 0;

        foreach ($request->details as $detail) {
            $product = Product::findOrFail($detail['product_id']);
            $subtotal = $product->price * $detail['quantity'];
            $grandTotal += $subtotal;
        }

        $transaction = Transaction::create([
            'customer_id' => $request->customer_id,
            'grand_total' => $grandTotal,
            'payment_method' => $request->payment_method,
            'status' => 'pending',
        ]);

        foreach ($request->details as $detail) {
            $product = Product::findOrFail($detail['product_id']);
            DetailTransaction::create([
                'transaction_id' => $transaction->id,
                'product_id' => $product->id,
                'quantity' => $detail['quantity'],
                'subtotal' => $product->price * $detail['quantity'],
                'status' => 'unpaid',
            ]);
        }

        return response()->json([
            'message' => 'Transaction created successfully',
            'transaction' => $transaction->load('details.product')
        ], 201);
    }

    /**
     * @OA\Put(
     *     path="/transactions/{id}/status",
     *     tags={"Transaction"},
     *     operationId="updateTransactionStatus",
     *     summary="Update transaction status",
     *     description="Admin can update the status of a transaction",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Transaction ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"status"},
     *             @OA\Property(property="status", type="string", enum={"pending", "paid", "shipped", "completed", "canceled"}, example="paid")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Transaction status updated",
     *         @OA\JsonContent(
     *             example={
     *                 "message": "Transaction status updated",
     *                 "transaction": {
     *                     "id": 1,
     *                     "status": "paid"
     *                 }
     *             }
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Unauthorized access"
     *     )
     * )
     */
    public function updateStatus(Request $request, $id)
    {
        if (Auth::user()->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'status' => 'required|string|in:pending,paid,shipped,completed,canceled'
        ]);

        $transaction = Transaction::findOrFail($id);
        $transaction->status = $request->status;
        $transaction->save();

        return response()->json([
            'message' => 'Transaction status updated',
            'transaction' => $transaction
        ]);
    }

    /**
     * @OA\Post(
     *     path="/transactions/{id}/cancel",
     *     tags={"Transaction"},
     *     operationId="cancelTransaction",
     *     summary="Cancel a transaction",
     *     description="Customer can cancel their own pending transactions",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Transaction ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Transaction canceled successfully",
     *         @OA\JsonContent(
     *             example={
     *                 "message": "Transaction canceled successfully",
     *                 "transaction": {
     *                     "id": 1,
     *                     "status": "canceled"
     *                 }
     *             }
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Unauthorized access"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Cannot cancel processed transaction"
     *     )
     * )
     */
    public function cancel($id)
    {
        $transaction = Transaction::findOrFail($id);

        if ($transaction->customer_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if ($transaction->status !== 'pending') {
            return response()->json(['message' => 'Cannot cancel transaction that is already processed'], 400);
        }

        $transaction->status = 'canceled';
        $transaction->save();

        return response()->json([
            'message' => 'Transaction canceled successfully',
            'transaction' => $transaction
        ]);
    }
}
