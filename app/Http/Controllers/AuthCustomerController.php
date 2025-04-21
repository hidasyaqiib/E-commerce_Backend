<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AuthCustomerService;
use App\Services\CustomerService;

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
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:customers,email',
            'phone' => 'required|string|max:15',
            'address' => 'required|string',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $result = $this->authService->register($request->all());

        return response()->json([
            'message' => 'Customer registered successfully',
            'customer' => $result['customer'],
            'token' => $result['token'],
        ], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $result = $this->authService->login($request->email, $request->password);

        return response()->json([
            'message' => 'Login successful',
            'customer' => $result['customer'],
            'token' => $result['token'],
        ]);
    }

    public function updateProfile(Request $request)
    {
        $customer = auth()->user();

        $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:customers,email,' . $customer->id,
            'phone' => 'sometimes|string|max:15',
            'address' => 'sometimes|string',
        ]);

        $customer->update($request->only(['name', 'email', 'phone', 'address']));

        return response()->json([
            'message' => 'Profile updated successfully',
            'customer' => $customer
        ]);
    }

    public function logout(Request $request)
    {
        $this->authService->logout($request->user());

        return response()->json(['message' => 'Logged out successfully']);
    }

    public function profile(Request $request)
    {
        $customer = $this->customerService->findById(auth()->id());

        return response()->json($customer);
    }

}
