<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Transaction;
use App\Models\DetailTransaction;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


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
     *     @OA\Response(response=201, description="Transaksi berhasil dibuat"),
     *     @OA\Response(response=422, description="Validasi gagal"),
     *     @OA\Response(response=500, description="Transaksi gagal")
     * )
     */
    public function store() {} // dummy

    /**
     * @OA\Get(
     *     path="/api/transactions",
     *     tags={"Transaction"},
     *     summary="Ambil daftar transaksi customer yang sedang login",
     *     security={{"sanctum":{}}},
     *     @OA\Response(response=200, description="Daftar transaksi berhasil diambil")
     * )
     */
    public function index() {} // dummy

    /**
     * @OA\Get(
     *     path="/api/transactions/{id}",
     *     tags={"Transaction"},
     *     summary="Ambil detail transaksi berdasarkan ID",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Berhasil mengambil detail transaksi"),
     *     @OA\Response(response=404, description="Transaksi tidak ditemukan")
     * )
     */
    public function show($id) {} // dummy

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
     *     @OA\Response(response=200, description="Status transaksi berhasil diperbarui"),
     *     @OA\Response(response=422, description="Validasi status gagal"),
     *     @OA\Response(response=404, description="Transaksi tidak ditemukan")
     * )
     */
    public function updateStatus($id) {} // dummy
}

