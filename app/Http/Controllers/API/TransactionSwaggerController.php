<?php

namespace App\Http\Controllers\API;

use App\Models\Cart;
use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\TransactionDetail;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use OpenApi\Annotations as OA;
use Illuminate\Support\Facades\Validator;

/**
 * @OA\Info(
 *     title="E-commerce API",
 *     version="1.0.0",
 *     description="API for managing transactions in E-commerce system"
 * )
 * 
 * @OA\SecurityScheme(
 *     securityScheme="sanctum",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT"
 * )
 */
class TransactionController extends Controller
{
    /**
     * @OA\Get(
     *     path="/transactions",
     *     operationId="getTransactions",
     *     tags={"Transaction"},
     *     summary="List all transactions for authenticated customer",
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="customer_id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="John Doe"),
     *                 @OA\Property(property="email", type="string", example="john@example.com"),
     *                 @OA\Property(property="phone", type="string", example="08123456789"),
     *                 @OA\Property(property="address", type="string", example="123 Main St"),
     *                 @OA\Property(property="payment_method", type="string", example="cash"),
     *                 @OA\Property(property="subtotal", type="integer", example=50000),
     *                 @OA\Property(property="status", type="string", example="pending"),
     *                 @OA\Property(
     *                     property="details",
     *                     type="array",
     *                     @OA\Items(
     *                         type="object",
     *                         @OA\Property(property="id", type="integer", example=1),
     *                         @OA\Property(property="transaction_id", type="integer", example=1),
     *                         @OA\Property(property="product_id", type="integer", example=1),
     *                         @OA\Property(property="quantity", type="integer", example=2),
     *                         @OA\Property(property="price", type="integer", example=25000),
     *                         @OA\Property(property="status", type="string", example="pending"),
     *                         @OA\Property(
     *                             property="product",
     *                             type="object",
     *                             @OA\Property(property="id", type="integer", example=1),
     *                             @OA\Property(property="name", type="string", example="Product Name")
     *                         )
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server Error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Internal server error")
     *         )
     *     )
     * )
     */
    public function index()
    {
        $transactions = Transaction::with(['details.product'])
            ->where('customer_id', Auth::id())
            ->get();

        return response()->json($transactions);
    }

    /**
     * @OA\Get(
     *     path="/transactions/{id}",
     *     operationId="getTransactionById",
     *     tags={"Transaction"},
     *     summary="Get transaction details by ID",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Transaction ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="customer_id", type="integer", example=1),
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(property="email", type="string", example="john@example.com"),
     *             @OA\Property(property="phone", type="string", example="08123456789"),
     *             @OA\Property(property="address", type="string", example="123 Main St"),
     *             @OA\Property(property="payment_method", type="string", example="cash"),
     *             @OA\Property(property="subtotal", type="integer", example=50000),
     *             @OA\Property(property="status", type="string", example="pending"),
     *             @OA\Property(
     *                 property="details",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="transaction_id", type="integer", example=1),
     *                     @OA\Property(property="product_id", type="integer", example=1),
     *                     @OA\Property(property="quantity", type="integer", example=2),
     *                     @OA\Property(property="price", type="integer", example=25000),
     *                     @OA\Property(property="status", type="string", example="pending"),
     *                     @OA\Property(
     *                         property="product",
     *                         type="object",
     *                         @OA\Property(property="id", type="integer", example=1),
     *                         @OA\Property(property="name", type="string", example="Product Name")
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not Found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Transaction not found")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server Error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Internal server error")
     *         )
     *     )
     * )
     */
    public function show($id)
    {
        $transaction = Transaction::with(['details.product'])
            ->where('id', $id)
            ->where('customer_id', Auth::id())
            ->first();

        if (!$transaction) {
            return response()->json(['message' => 'Transaction not found'], 404);
        }

        return response()->json($transaction);
    }

    /**
     * @OA\Post(
     *     path="/transactions",
     *     operationId="createTransaction",
     *     tags={"Transaction"},
     *     summary="Create new transaction",
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "email", "phone", "address", "payment_method", "products"},
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(property="email", type="string", format="email", example="john@example.com"),
     *             @OA\Property(property="phone", type="string", example="08123456789"),
     *             @OA\Property(property="address", type="string", example="123 Main St"),
     *             @OA\Property(
     *                 property="payment_method",
     *                 type="string",
     *                 enum={"cash", "credit_card", "bank_transfer"},
     *                 example="cash"
     *             ),
     *             @OA\Property(
     *                 property="products",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     required={"product_id", "quantity"},
     *                     @OA\Property(property="product_id", type="integer", example=1),
     *                     @OA\Property(property="quantity", type="integer", example=2)
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Transaction created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Transaction created successfully"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="customer_id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="John Doe"),
     *                 @OA\Property(property="email", type="string", example="john@example.com"),
     *                 @OA\Property(property="phone", type="string", example="08123456789"),
     *                 @OA\Property(property="address", type="string", example="123 Main St"),
     *                 @OA\Property(property="payment_method", type="string", example="cash"),
     *                 @OA\Property(property="subtotal", type="integer", example=50000),
     *                 @OA\Property(property="status", type="string", example="pending"),
     *                 @OA\Property(
     *                     property="details",
     *                     type="array",
     *                     @OA\Items(
     *                         type="object",
     *                         @OA\Property(property="id", type="integer", example=1),
     *                         @OA\Property(property="product_id", type="integer", example=1),
     *                         @OA\Property(property="quantity", type="integer", example=2),
     *                         @OA\Property(property="price", type="integer", example=25000),
     *                         @OA\Property(property="status", type="string", example="pending"),
     *                         @OA\Property(
     *                             property="product",
     *                             type="object",
     *                             @OA\Property(property="id", type="integer", example=1),
     *                             @OA\Property(property="name", type="string", example="Product Name")
     *                         )
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation Error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Validation failed"),
     *             @OA\Property(property="errors", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server Error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Transaction failed"),
     *             @OA\Property(property="error", type="string", example="Error message")
     *         )
     *     )
     * )
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|email',
            'phone' => 'required|string',
            'address' => 'required|string',
            'payment_method' => 'required|in:cash,credit_card,bank_transfer',
            'products' => 'required|array|min:1',
            'products.*.product_id' => 'required|integer|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Validation failed', 'errors' => $validator->errors()], 422);
        }

        DB::beginTransaction();

        try {
            $subtotal = 0;
            foreach ($request->products as $item) {
                $product = Product::find($item['product_id']);
                $subtotal += $product->price * $item['quantity'];
            }

            $transaction = Transaction::create([
                'customer_id' => Auth::id(),
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address,
                'payment_method' => $request->payment_method,
                'subtotal' => $subtotal,
                'status' => 'pending',
            ]);

            foreach ($request->products as $item) {
                $product = Product::find($item['product_id']);
                TransactionDetail::create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'status' => 'pending',
                    'price' => $product->price,
                ]);
            }

            Cart::where('customer_id', Auth::id())->delete();

            DB::commit();

            return response()->json([
                'message' => 'Transaction created successfully',
                'data' => $transaction->load('details.product')
            ], 201);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['message' => 'Transaction failed', 'error' => $th->getMessage()], 500);
        }
    }

    /**
     * @OA\Put(
     *     path="/transactions/{id}/status",
     *     operationId="updateTransactionStatus",
     *     tags={"Transaction"},
     *     summary="Update transaction status (Admin only)",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Transaction ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"status"},
     *             @OA\Property(
     *                 property="status",
     *                 type="string",
     *                 enum={"pending", "paid", "cancelled"},
     *                 example="paid"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Status updated",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Transaction status updated to paid")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated")
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthorized action")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not Found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Transaction not found")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation Error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Validation failed"),
     *             @OA\Property(property="errors", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server Error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Failed to update status"),
     *             @OA\Property(property="error", type="string", example="Error message")
     *         )
     *     )
     * )
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,paid,cancelled'
        ]);

        $transaction = Transaction::find($id);
        if (!$transaction) {
            return response()->json(['message' => 'Transaction not found'], 404);
        }

        $transaction->status = $request->status;
        $transaction->save();

        return response()->json(['message' => 'Transaction status updated to ' . $request->status]);
    }
}