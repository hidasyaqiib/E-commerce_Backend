<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\SalesReport;
use Illuminate\Http\Request;

/**
 * @OA\Schema(
 *     schema="SalesReport",
 *     type="object",
 *     required={"id", "period", "total_sales", "total_revenue"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="period", type="string", example="April 2025"),
 *     @OA\Property(property="total_sales", type="number", example=100),
 *     @OA\Property(property="total_revenue", type="number", example=500000)
 * )
 */

class SalesReportSwaggerController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/sales-reports",
     *     summary="Get all sales reports",
     *     tags={"Sales Report"},
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/SalesReport"))
     *     )
     * )
     */
    public function index()
    {
        return response()->json(SalesReport::all());
    }

    /**
     * @OA\Get(
     *     path="/api/sales-reports/{id}",
     *     summary="Get sales report by ID",
     *     tags={"Sales Report"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(ref="#/components/schemas/SalesReport")
     *     ),
     *     @OA\Response(response=404, description="Not Found")
     * )
     */
    public function show($id)
    {
        $salesReport = SalesReport::find($id);
        if (!$salesReport) {
            return response()->json(['message' => 'Report not found'], 404);
        }
        return response()->json($salesReport);
    }

    /**
     * @OA\Post(
     *     path="/api/sales-reports",
     *     summary="Create new sales report",
     *     tags={"Sales Report"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/SalesReportCreate")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Created",
     *         @OA\JsonContent(ref="#/components/schemas/SalesReport")
     *     )
     * )
     */
    public function store(Request $request)
    {
        $request->validate([
            'period' => 'required|string|max:255',
            'total_sales' => 'required|numeric',
            'total_revenue' => 'required|numeric',
        ]);

        $salesReport = SalesReport::create($request->all());

        return response()->json($salesReport, 201);
    }

    /**
     * @OA\Put(
     *     path="/api/sales-reports/{id}",
     *     summary="Update sales report",
     *     tags={"Sales Report"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/SalesReportUpdate")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Updated",
     *         @OA\JsonContent(ref="#/components/schemas/SalesReport")
     *     ),
     *     @OA\Response(response=404, description="Not Found")
     * )
     */
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

        return response()->json($salesReport);
    }

    /**
     * @OA\Delete(
     *     path="/api/sales-reports/{id}",
     *     summary="Delete sales report",
     *     tags={"Sales Report"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Deleted"),
     *     @OA\Response(response=404, description="Not Found")
     * )
     */
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
