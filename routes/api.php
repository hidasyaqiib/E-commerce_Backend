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
// Route Tanpa Token
// ==========================

// Admin
Route::post('/admin/register', [AuthAdminController::class, 'register']);
Route::post('/admin/login', [AuthAdminController::class, 'login']);
// Customer
Route::post('/customer/register', [AuthCustomerController::class, 'register']);
Route::post('/customer/login', [AuthCustomerController::class, 'login']);


// ==========================
// ADMIN ROUTES
// ==========================
Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
    // Protected Route
    Route::post('/admin/logout', [AuthAdminController::class, 'logout']);
    Route::delete('/admin/destroy', [AuthAdminController::class, 'destroy']);
    Route::get('/admins', [AuthAdminController::class, 'get']);
    Route::put('/admin/profile', [AuthAdminController::class, 'updateProfile']);
    Route::apiResource('/customers', CustomerController::class);

    // Store
    Route::apiResource('stores', StoreController::class)->except(['index']);
    Route::get('my-store', [StoreController::class, 'myStore']);

    // Category
    Route::apiResource('/categories', CategoryController::class);

    // Product
    Route::apiResource('/products', ProductController::class);

    // transaction
    Route::apiResource('/transactions', TransactionController::class);
    Route::put('/transactions/{id}/status', [TransactionController::class, 'updateStatus']);

    Route::get('/sales-report', [SalesReportController::class, 'index']);

});


// ==========================
// CUSTOMER ROUTES
// ==========================

Route::middleware(['auth:sanctum', 'role:customer'])->group(function () {
    // Protected Route
    Route::get('/profile', [AuthCustomerController::class, 'profile']);
    Route::put('/profile', [AuthCustomerController::class, 'updateProfile']);
    Route::post('/logout', [AuthCustomerController::class, 'logout']);

    // Transaksi customer
    Route::post('/transactions', [TransactionController::class, 'store']);
    Route::get('/transactions', [TransactionController::class, 'index']);
    Route::get('/transactions/{id}', [TransactionController::class, 'show']);
});


// ==========================
// CUSTOMER & Admin ROUTES
// ==========================

Route::middleware(['auth:sanctum', 'role:admin,customer'])->group(function () {

    // Product
    Route::get('/products', [ProductController::class, 'index']);
    Route::get('/products/{id}', [ProductController::class, 'show']);
    Route::get('/products/{categoryId}', [ProductController::class, 'getByCategory']);
    Route::get('/products/{storeId}', [ProductController::class, 'getByStore']);

});
