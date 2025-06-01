<?php
// ============================================
// 7. UPDATED API ROUTES (Integrated with your existing routes)
// ============================================
// File: routes/api.php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthAdminController;
use App\Http\Controllers\AuthCustomerController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\DetailTransactionController;
use App\Http\Controllers\SalesReportController;

// ==========================
// AUTH
// ==========================

// Admin
Route::post('/admin/register', [AuthAdminController::class, 'register']);
Route::post('/admin/login', [AuthAdminController::class, 'login']);

// Customer
Route::post('/customer/register', [AuthCustomerController::class, 'register']);
Route::post('/customer/login', [AuthCustomerController::class, 'login']);

// Logout & Profile (semua)
Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/admin/logout', [AuthAdminController::class, 'logout']);
    Route::post('/customer/logout', [AuthCustomerController::class, 'logout']);
    Route::get('/customer/profile', [AuthCustomerController::class, 'profile']);

    // Add profile update route
    Route::put('/customer/profile', [AuthCustomerController::class, 'updateProfile']);
});

// ==========================
// ADMIN ROUTES
// ==========================
Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
    Route::get('/admins', [AuthAdminController::class, 'get']);
    Route::apiResource('/customers', CustomerController::class);

    Route::apiResource('/transactions', TransactionController::class);
    Route::put('/transactions/{id}/status', [TransactionController::class, 'updateStatus']);
    Route::put('/transactions/{id}/cancel', [TransactionController::class, 'cancel']);
    Route::get('/detail-transactions', [DetailTransactionController::class, 'index']);

    Route::apiResource('/sales-reports', SalesReportController::class);
});

// ==========================
// CUSTOMER ROUTES
// ==========================
Route::middleware(['auth:sanctum', 'role:customer'])->group(function () {
    // Store
    Route::apiResource('/stores', StoreController::class);

    // Product milik store user login
    Route::get('/products/my-store', [ProductController::class, 'myStoreProducts']);
    Route::apiResource('/products', ProductController::class);

    // Category hanya untuk store user sendiri
    Route::apiResource('/categories', CategoryController::class);

    // Transaksi customer sendiri
    Route::get('/my-transactions', [TransactionController::class, 'myTransactions']);
    Route::get('/detail-transactions/{transaction_id}', [DetailTransactionController::class, 'show']);
});
