<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\DetailTransaction;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


/**
 *
 * @OA\Tag(
 *     name="Transaction",
 *     description="API untuk manajemen transaksi customer"
 * )
 */
class TransactionSwaggerController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/transactions",
     *     tags={"Transaction"},
     *     summary="Buat transaksi baru",
     *     description="Membuat transaksi baru oleh customer, mengurangi stok produk dan menyimpan detail transaksi",
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name","email","phone","address","payment_method","products"},
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(property="email", type="string", format="email", example="john@example.com"),
     *             @OA\Property(property="phone", type="string", example="08123456789"),
     *             @OA\Property(property="address", type="string", example="Jl. Merdeka No. 1"),
     *             @OA\Property(property="payment_method", type="string", enum={"cash","credit_card","bank_transfer"}, example="cash"),
     *             @OA\Property(
     *                 property="products",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     required={"product_id","quantity"},
     *                     @OA\Property(property="product_id", type="integer", example=101),
     *                     @OA\Property(property="quantity", type="integer", example=2)
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Transaksi berhasil dibuat",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Transaction created successfully"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validasi gagal",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The given data was invalid."),
     *             @OA\Property(property="errors", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Transaksi gagal",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Transaction failed"),
     *             @OA\Property(property="error", type="string", example="Insufficient stock for product T-Shirt")
     *         )
     *     )
     * )
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'           => 'required|string',
            'email'          => 'required|email',
            'phone'          => ['required', 'regex:/^[0-9]+$/'],
            'address'        => 'required|string',
            'payment_method' => 'required|in:cash,credit_card,bank_transfer',
            'products'       => 'required|array|min:1',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity'   => 'required|integer|min:1',
        ]);

        $customer = Auth::guard('customer')->user();

        DB::beginTransaction();

        try {
            $subtotal = 0;
            $products = [];

            foreach ($validated['products'] as $item) {
                $product = Product::findOrFail($item['product_id']);

                if ($product->stock < $item['quantity']) {
                    throw new \Exception("Insufficient stock for product {$product->name}");
                }

                $products[] = ['model' => $product, 'quantity' => $item['quantity']];
                $subtotal += $product->price * $item['quantity'];
            }

            if ($subtotal <= 0) {
                throw new \Exception('Subtotal must be greater than zero');
            }

            $transaction = Transaction::create([
                'customer_id'    => $customer->id,
                'name'           => $validated['name'],
                'email'          => $validated['email'],
                'phone'          => $validated['phone'],
                'address'        => $validated['address'],
                'payment_method' => $validated['payment_method'],
                'subtotal'       => $subtotal,
            ]);

            foreach ($products as $item) {
                $status = $validated['payment_method'] === 'cash' ? 'pending' : 'paid';

                DetailTransaction::create([
                    'transaction_id' => $transaction->id,
                    'product_id'     => $item['model']->id,
                    'quantity'       => $item['quantity'],
                    'status'         => $status,
                ]);

                $item['model']->decrement('stock', $item['quantity']);
            }

            DB::commit();

            return response()->json([
                'message' => 'Transaction created successfully',
                'data'    => $transaction->load('details.product'),
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'Transaction failed',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/transactions",
     *     tags={"Transaction"},
     *     summary="Ambil daftar transaksi customer yang sedang login",
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Daftar transaksi berhasil diambil",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(type="object")
     *         )
     *     )
     * )
     */
    public function index()
    {
        $customer = Auth::guard('customer')->user();

        $transactions = Transaction::with('details.product')
            ->where('customer_id', $customer->id)
            ->get();

        return response()->json($transactions);
    }

    /**
     * @OA\Get(
     *     path="/api/transactions/{id}",
     *     tags={"Transaction"},
     *     summary="Ambil detail transaksi berdasarkan ID",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(
     *         response=200,
     *         description="Berhasil mengambil detail transaksi",
     *         @OA\JsonContent(type="object")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Transaksi tidak ditemukan",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Transaction not found")
     *         )
     *     )
     * )
     */
    public function show($id)
    {
        $customer = Auth::guard('customer')->user();

        $transaction = Transaction::with('details.product')
            ->where('id', $id)
            ->where('customer_id', $customer->id)
            ->firstOrFail();

        return response()->json($transaction);
    }

    /**
     * @OA\Patch(
     *     path="/api/transactions/{id}/status",
     *     tags={"Transaction"},
     *     summary="Update status semua detail transaksi",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"status"},
     *             @OA\Property(property="status", type="string", enum={"pending","paid","cancelled"}, example="paid")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Status transaksi berhasil diperbarui",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Transaction status updated to paid")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validasi status gagal",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The given data was invalid."),
     *             @OA\Property(property="errors", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Transaksi tidak ditemukan",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Transaction not found")
     *         )
     *     )
     * )
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,paid,cancelled'
        ]);

        $transaction = Transaction::with('details')->findOrFail($id);

        foreach ($transaction->details as $detail) {
            $detail->update(['status' => $request->status]);
        }

        return response()->json([
            'message' => 'Transaction status updated to ' . $request->status
        ]);
    }
}
