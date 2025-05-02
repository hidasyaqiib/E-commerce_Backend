<?php

namespace App\Http\Controllers;

use App\Models\SalesReport;
use Illuminate\Http\Request;

class SalesReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');  // Pastikan hanya yang terautentikasi yang bisa akses
        $this->middleware('admin'); // Pastikan hanya admin yang bisa akses
    }

    // Menampilkan semua laporan penjualan
    public function index()
    {
        $salesReports = SalesReport::all();
        return response()->json($salesReports);
    }

    // Menampilkan laporan penjualan berdasarkan ID
    public function show($id)
    {
        $salesReport = SalesReport::find($id);

        if (!$salesReport) {
            return response()->json(['message' => 'Report not found'], 404);
        }

        return response()->json($salesReport);
    }

    // Membuat laporan penjualan baru
    public function store(Request $request)
    {
        $request->validate([
            'period' => 'required|string|max:255',
            'total_sales' => 'required|numeric',
            'total_revenue' => 'required|numeric',
        ]);

        $salesReport = SalesReport::create($request->all());

        return response()->json([
            'message' => 'Sales report created successfully',
            'data' => $salesReport
        ], 201);
    }

    // Memperbarui laporan penjualan
    public function update(Request $request, $id)
    {
        $salesReport = SalesReport::find($id);

        if (!$salesReport) {
            return response()->json(['message' => 'Report not found'], 404);
        }

        $request->validate([
            'period' => 'sometimes|string|max:255',
            'total_sales' => 'sometimes|numeric',
            'total_revenue' => 'sometimes|numeric',
        ]);

        $salesReport->update($request->all());

        return response()->json([
            'message' => 'Sales report updated successfully',
            'data' => $salesReport
        ]);
    }

    // Menghapus laporan penjualan
    public function destroy($id)
    {
        $salesReport = SalesReport::find($id);

        if (!$salesReport) {
            return response()->json(['message' => 'Report not found'], 404);
        }

        $salesReport->delete();

        return response()->json(['message' => 'Sales report deleted successfully']);
    }
}
