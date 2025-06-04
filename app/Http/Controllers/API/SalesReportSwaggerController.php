<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\SalesReport;
use Illuminate\Http\Request;

/**
 *
 * @OA\Tag(
 *     name="SalesReport",
 *     description="API untuk laporan penjualan per toko admin"
 * )
 */

class SalesReportSwaggerController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/sales-report",
     *     tags={"SalesReport"},
     *     summary="Mendapatkan laporan penjualan berdasarkan store milik admin yang login",
     *     description="Mengembalikan list produk beserta total quantity terjual dan total penjualan (dalam rupiah) untuk toko admin saat ini",
     *     security={{"sanctum": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Berhasil mendapatkan laporan penjualan",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="store_id", type="integer", example=1),
     *             @OA\Property(property="admin_id", type="integer", example=10),
     *             @OA\Property(
     *                 property="sales_report",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="product_id", type="integer", example=101),
     *                     @OA\Property(property="product_name", type="string", example="Produk A"),
     *                     @OA\Property(property="total_quantity", type="integer", example=50),
     *                     @OA\Property(property="total_sales", type="number", format="float", example=1500000)
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized, token tidak valid atau tidak login",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Kesalahan server internal",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Internal Server Error")
     *         )
     *     )
     * )
     */
    public function index() {} // dummy function biar Swagger valid
}
