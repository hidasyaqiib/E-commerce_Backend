<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\Services\CustomerService;
use App\Services\AuthCustomerService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Customer;
/**
 * @OA\Tag(
 *     name="Customer",
 *     description="API untuk manajemen pelanggan"
 * )
 */

class CustomerSwaggerController extends Controller
{
    protected $customerService;

    public function __construct(CustomerService $customerService)
    {
        $this->customerService = $customerService;
    }

    /**
     * @OA\Get(
     *     path="/api/customers",
     *     summary="Get all customers",
     *     tags={"Customer"},
     *     @OA\Response(response=200, description="Customers retrieved successfully"),
     *     @OA\Response(response=500, description="Failed to retrieve customers")
     * )
     */
    public function index()
    {
        try {
            $customers = $this->customerService->getAllCustomers();

            return response()->json([
                'success' => true,
                'message' => 'Customers retrieved successfully',
                'data' => $customers
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve customers',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/customers/{id}",
     *     summary="Get a customer by ID",
     *     tags={"Customer"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Customer retrieved successfully"),
     *     @OA\Response(response=404, description="Customer not found")
     * )
     */
    public function show($id)
    {
        try {
            $customer = $this->customerService->getCustomer($id);

            return response()->json([
                'success' => true,
                'message' => 'Customer retrieved successfully',
                'data' => $customer
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Customer not found',
                'error' => $e->getMessage()
            ], 404);
        }
    }
}
