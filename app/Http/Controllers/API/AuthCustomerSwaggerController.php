<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AuthCustomerSwaggerController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/customer/register",
     *     summary="Register a new customer",
     *     tags={"Customer Auth"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name","email","phone","address","password","password_confirmation"},
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(property="email", type="string", format="email", example="john@example.com"),
     *             @OA\Property(property="phone", type="string", example="08123456789"),
     *             @OA\Property(property="address", type="string", example="Jl. Mawar No. 1"),
     *             @OA\Property(property="password", type="string", format="password", example="secret123"),
     *             @OA\Property(property="password_confirmation", type="string", format="password", example="secret123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Customer registered successfully"
     *     ),
     *     @OA\Response(response=422, description="Validation failed"),
     *     @OA\Response(response=500, description="Registration failed")
     * )
     */
    public function register() {}

    /**
     * @OA\Post(
     *     path="/api/customer/login",
     *     summary="Login customer and get token",
     *     tags={"Customer Auth"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email","password"},
     *             @OA\Property(property="email", type="string", format="email", example="john@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="secret123")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Login successful"),
     *     @OA\Response(response=401, description="Login failed"),
     *     @OA\Response(response=500, description="Login failed")
     * )
     */
    public function login() {}

    /**
     * @OA\Get(
     *     path="/api/customer/profile",
     *     summary="Get authenticated customer profile",
     *     tags={"Customer Auth"},
     *     security={{"sanctum":{}}},
     *     @OA\Response(response=200, description="Profile retrieved successfully"),
     *     @OA\Response(response=500, description="Failed to get profile")
     * )
     */
    public function profile() {}

    /**
     * @OA\Put(
     *     path="/api/customer/update-profile",
     *     summary="Update authenticated customer profile",
     *     tags={"Customer Auth"},
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="Jane Doe"),
     *             @OA\Property(property="email", type="string", format="email", example="jane@example.com"),
     *             @OA\Property(property="phone", type="string", example="08123456789"),
     *             @OA\Property(property="address", type="string", example="Jl. Anggrek No. 2")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Profile updated successfully"),
     *     @OA\Response(response=422, description="Validation failed"),
     *     @OA\Response(response=500, description="Profile update failed")
     * )
     */
    public function updateProfile() {}

    /**
     * @OA\Post(
     *     path="/api/customer/logout",
     *     summary="Logout authenticated customer",
     *     tags={"Customer Auth"},
     *     security={{"sanctum":{}}},
     *     @OA\Response(response=200, description="Logged out successfully"),
     *     @OA\Response(response=500, description="Logout failed")
     * )
     */
    public function logout() {}
}
