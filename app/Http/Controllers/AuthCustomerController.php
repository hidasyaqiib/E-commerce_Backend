<?php

// ============================================
// 5. AUTH CUSTOMER CONTROLLER
// ============================================
// File: app/Http/Controllers/AuthCustomerController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AuthCustomerService;
use App\Services\CustomerService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthCustomerController extends Controller
{
    protected $authService;
    protected $customerService;

    public function __construct(AuthCustomerService $authService, CustomerService $customerService)
    {
        $this->authService = $authService;
        $this->customerService = $customerService;
    }

    public function register(Request $request)
{
    try {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',  // <- Ubah ke users, bukan customers
            'phone' => 'required|string|max:15',
            'address' => 'required|string',
            'password' => 'required|min:6|confirmed',
        ]);

        $result = $this->authService->register($validated);

        return response()->json([
            'success' => true,
            'message' => 'Customer registered successfully',
            'data' => [
                'customer' => $result['customer'],
                'token' => $result['token'],
            ]
        ], 201);

    } catch (ValidationException $e) {
        return response()->json([
            'success' => false,
            'message' => 'Validation failed',
            'errors' => $e->errors()
        ], 422);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Registration failed',
            'error' => $e->getMessage()
        ], 500);
    }
}

    public function login(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);

            $result = $this->authService->login($request->email, $request->password);

            return response()->json([
                'success' => true,
                'message' => 'Login successful',
                'data' => [
                    'customer' => $result['customer'],
                    'token' => $result['token'],
                ]
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Login failed',
                'errors' => $e->errors()
            ], 401);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Login failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function profile(Request $request)
    {
        try {
            $customer = $request->user();
            
            return response()->json([
                'success' => true,
                'message' => 'Profile retrieved successfully',
                'data' => [
                    'customer' => $customer
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get profile',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function updateProfile(Request $request)
    {
        try {
            $customer = $request->user();

            $validated = $request->validate([
    'name' => 'sometimes|string|max:255',
    'email' => 'sometimes|email|unique:users,email,' . $customer->user_id, // <- Ubah ke users
    'phone' => 'sometimes|string|max:15',
    'address' => 'sometimes|string',
]);

            $updatedCustomer = $this->customerService->updateCustomer($customer, $validated);

            return response()->json([
                'success' => true,
                'message' => 'Profile updated successfully',
                'data' => [
                    'customer' => $updatedCustomer
                ]
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Profile update failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function logout(Request $request)
    {
        try {
            $customer = $request->user();
            $this->authService->logout($customer);

            return response()->json([
                'success' => true,
                'message' => 'Logged out successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Logout failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}

