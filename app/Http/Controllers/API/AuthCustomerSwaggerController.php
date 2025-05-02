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
     *     tags={"Customer Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="email", type="string", format="email"),
     *             @OA\Property(property="phone", type="string"),
     *             @OA\Property(property="address", type="string"),
     *             @OA\Property(property="password", type="string"),
     *             @OA\Property(property="password_confirmation", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Customer registered successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string"),
     *             @OA\Property(property="user", type="object"),
     *             @OA\Property(property="customer", type="object"),
     *             @OA\Property(property="token", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid input"
     *     )
     * )
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email', // <- validasi ke tabel users
            'phone' => 'required|string|max:15',
            'address' => 'required|string',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $result = $this->authService->register($request->all());

        return response()->json([
            'message' => 'Customer registered successfully',
            'user' => $result['user'],
            'customer' => $result['customer'],
            'token' => $result['token'],
        ], 201);
    }

    /**
     * @OA\Post(
     *     path="/api/customer/login",
     *     summary="Login a customer",
     *     tags={"Customer Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="email", type="string", format="email"),
     *             @OA\Property(property="password", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Login successful",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string"),
     *             @OA\Property(property="user", type="object"),
     *             @OA\Property(property="customer", type="object"),
     *             @OA\Property(property="token", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Invalid credentials"
     *     )
     * )
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $result = $this->authService->login($request->email, $request->password);

        return response()->json([
            'message' => 'Login successful',
            'user' => $result['user'],
            'customer' => $result['customer'],
            'token' => $result['token'],
        ]);
    }

    /**
     * @OA\Put(
     *     path="/api/customer/profile",
     *     summary="Update customer profile",
     *     tags={"Customer Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="email", type="string", format="email"),
     *             @OA\Property(property="phone", type="string"),
     *             @OA\Property(property="address", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Profile updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string"),
     *             @OA\Property(property="user", type="object"),
     *             @OA\Property(property="customer", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid input"
     *     )
     * )
     */
    public function updateProfile(Request $request)
    {
        $user = auth()->user();
        $customer = $user->customer;

        $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:users,email,' . $user->id,
            'phone' => 'sometimes|string|max:15',
            'address' => 'sometimes|string',
        ]);

        // Update data user
        $user->update($request->only('email'));

        // Update data customer
        $customer->update($request->only('name', 'phone', 'address'));

        return response()->json([
            'message' => 'Profile updated successfully',
            'user' => $user,
            'customer' => $customer,
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/customer/logout",
     *     summary="Logout customer",
     *     tags={"Customer Authentication"},
     *     @OA\Response(
     *         response=200,
     *         description="Logged out successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Logout failed"
     *     )
     * )
     */
    public function logout(Request $request)
    {
        $this->authService->logout($request->user());

        return response()->json(['message' => 'Logged out successfully']);
    }

    /**
     * @OA\Get(
     *     path="/api/customer/profile",
     *     summary="Get customer profile",
     *     tags={"Customer Authentication"},
     *     @OA\Response(
     *         response=200,
     *         description="Profile retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="user", type="object"),
     *             @OA\Property(property="customer", type="object"),
     *             @OA\Property(property="role", type="array", @OA\Items(type="string"))
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     )
     * )
     */
    public function profile(Request $request)
    {
        $user = auth()->user();
        $customer = $user->customer;

        return response()->json([
            'user' => $user,
            'customer' => $customer,
            'role' => $user->getRoleNames()
        ]);
    }
}
