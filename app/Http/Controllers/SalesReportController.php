<?php

namespace App\Http\Controllers;

use App\Models\DetailTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SalesReportController extends Controller
{
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
    