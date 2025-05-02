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
     *     description="Retrieve all detail transactions with their related transaction and product",
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             example={
     *                 {
     *                     "id": 1,
     *                     "transaction_id": 1,
     *                     "product_id": 1,
     *                     "quantity": 2,
     *                     "status": "pending"
     *                 }
     *             }
     *         )
     *     )
     * )
     */
    public function index()
    {
        //
    }

    /**
     * @OA\Get(
     *     path="/detail-transactions/{transaction_id}",
     *     tags={"DetailTransaction"},
     *     operationId="showDetailTransaction",
     *     summary="Show details of a specific transaction",
     *     description="Retrieve detail transactions by transaction ID",
     *     @OA\Parameter(
     *         name="transaction_id",
     *         in="path",
     *         description="ID of the transaction",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             example={
     *                 "transaction": {
     *                     "id": 1,
     *                     "customer_id": 1,
     *                     "total_amount": 50000
     *                 },
     *                 "details": {
     *                     {
     *                         "id": 1,
     *                         "product_id": 1,
     *                         "quantity": 2,
     *                         "status": "pending"
     *                     }
     *                 }
     *             }
     *         )
     *     )
     * )
     */
    public function show($transaction_id)
    {
        $details = DetailTransaction::with(['transaction.customer', 'product'])->get();
        return response()->json($details);
    }

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
     *             example={
     *                 "message": "Detail transaction added successfully",
     *                 "detail": {
     *                     "id": 5,
     *                     "transaction_id": 1,
     *                     "product_id": 2,
     *                     "quantity": 3,
     *                     "status": "pending"
     *                 }
     *             }
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Unauthorized"
     *     )
     * )
     */
    public function store(Request $request)
    {
        // Hanya admin yang boleh akses
        if (auth()->user()->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Validasi input
        $validated = $request->validate([
            'transaction_id' => 'required|exists:transactions,id',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        // Simpan detail transaksi
        $detail = DetailTransaction::create([
            'transaction_id' => $validated['transaction_id'],
            'product_id' => $validated['product_id'],
            'quantity' => $validated['quantity'],
            'status' => 'pending',
        ]);

        // Ambil data transaksi & detail untuk ditampilkan kembali
        $transaction = Transaction::findOrFail($validated['transaction_id']);
        $details = DetailTransaction::where('transaction_id', $validated['transaction_id'])
            ->with('product')
            ->get();

        return response()->json([
            'message' => 'Detail transaction added successfully',
            'transaction' => $transaction,
            'details' => $details,
        ], 201);
    }

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
     *         description="ID of the detail transaction to update",
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
     *             example={
     *                 "message": "Detail transaction updated successfully",
     *                 "detail": {
     *                     "id": 1,
     *                     "transaction_id": 1,
     *                     "product_id": 1,
     *                     "quantity": 5,
     *                     "status": "pending"
     *                 }
     *             }
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Unauthorized"
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        if (auth()->user()->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $detail = DetailTransaction::findOrFail($id);
        $detail->quantity = $request->quantity;
        $detail->save();

        return response()->json([
            'message' => 'Detail transaction updated successfully',
            'detail' => $detail,
        ]);
    }

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
     *         description="ID of the detail transaction to delete",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Detail transaction deleted successfully",
     *         @OA\JsonContent(
     *             example={
     *                 "message": "Detail transaction deleted successfully"
     *             }
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Unauthorized"
     *     )
     * )
     */
    public function destroy($id)
    {
        if (auth()->user()->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $detail = DetailTransaction::findOrFail($id);
        $detail->delete();

        return response()->json(['message' => 'Detail transaction deleted successfully']);
    }
}
