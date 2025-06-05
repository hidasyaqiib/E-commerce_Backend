<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\SalesReport;
use Illuminate\Http\Request;
use App\Models\DetailTransaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
     *     summary="Get sales report for the admin's store",
     *     tags={"SalesReport"},
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Sales report retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="store_id", type="integer", example=1),
     *             @OA\Property(property="admin_id", type="integer", example=3),
     *             @OA\Property(
     *                 property="sales_report",
     *                 type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="product_id", type="integer", example=5),
     *                     @OA\Property(property="product_name", type="string", example="T-Shirt"),
     *                     @OA\Property(property="total_quantity", type="integer", example=30),
     *                     @OA\Property(property="total_sales", type="number", format="float", example=360000)
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated.")
     *         )
     *     )
     * )
     */
    public function index(Request $request)
    {
        $admin = Auth::guard('admin')->user();
        $storeId = $admin->store->id; // pastikan relasi admin -> store sudah dibuat

        // Ambil penjualan dari produk yang dijual oleh store milik admin ini
        $report = DetailTransaction::select(
                'products.id as product_id',
                'products.name as product_name',
                DB::raw('SUM(detail_transactions.quantity) as total_quantity'),
                DB::raw('SUM(detail_transactions.quantity * products.price) as total_sales')
            )
            ->join('products', 'detail_transactions.product_id', '=', 'products.id')
            ->where('products.store_id', $storeId)
            ->groupBy('products.id', 'products.name')
            ->get();

        return response()->json([
            'store_id' => $storeId,
            'admin_id' => $admin->id,
            'sales_report' => $report
        ]);
    }
}
