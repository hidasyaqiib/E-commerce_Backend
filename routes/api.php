<?php

// Admin Controllers
use App\Http\Controllers\API\AdminSwaggerController;
use App\Http\Controllers\AuthAdminController;
use App\Http\Controllers\AuthCustomerController;

// Customer Controllers
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\API\CustomerSwaggerController;
use App\Http\Controllers\API\AuthCustomerSwaggerController;

// Store Controllers
use App\Http\Controllers\StoreController;
use App\Http\Controllers\API\StoreSwaggerController;

// Category Controllers
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\API\CategorySwaggerController;

// Product Controllers
use App\Http\Controllers\ProductController;
use App\Http\Controllers\API\ProductSwaggerController;

// Transaction Controllers
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\API\TransactionSwaggerController;

// Sales Report Controllers
use App\Http\Controllers\SalesReportController;
use App\Http\Controllers\API\SalesReportSwaggerController;

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

    // sales report
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

// ==========================
// Swagger Route
// ==========================

// ADMIN
Route::post('/admin/register', [AdminSwaggerController::class, 'register']);
Route::post('/admin/login', [AdminSwaggerController::class, 'login']);

    Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
    // Protected Route
    Route::post('/admin/logout', [AdminSwaggerController::class, 'logout']);
    Route::delete('/admin/destroy', [AdminSwaggerController::class, 'destroy']);
    Route::get('/admins', [AdminSwaggerController::class, 'get']);
    Route::put('/admin/profile', [AdminSwaggerController::class, 'updateProfile']);
    Route::apiResource('/customers', CustomerSwaggerController::class);
    // Store
    Route::apiResource('stores', StoreSwaggerController::class)->except(['index']);
    Route::get('my-store', [StoreSwaggerController::class, 'myStore']);
    // Category
    Route::apiResource('/categories', CategorySwaggerController::class);
    // Product
    Route::apiResource('/products', ProductSwaggerController::class);
    // transaction
    Route::apiResource('/transactions', TransactionSwaggerController::class);
    Route::put('/transactions/{id}/status', [TransactionSwaggerController::class, 'updateStatus']);
    // sales report
    Route::get('/sales-report', [SalesReportSwaggerController::class, 'index']);

});


// CUSTOMER
Route::post('/customer/register', [AuthCustomerSwaggerController::class, 'register']);
Route::post('/customer/login', [AuthCustomerSwaggerController::class, 'login']);

Route::middleware(['auth:sanctum', 'role:customer'])->group(function () {
    // Protected Route
    Route::get('/profile', [AuthCustomerSwaggerController::class, 'profile']);
    Route::put('/profile', [AuthCustomerSwaggerController::class, 'updateProfile']);
    Route::post('/logout', [AuthCustomerSwaggerController::class, 'logout']);

    // Transaksi customer
    Route::post('/transactions', [TransactionSwaggerController::class, 'store']);
    Route::get('/transactions', [TransactionSwaggerController::class, 'index']);
    Route::get('/transactions/{id}', [TransactionSwaggerController::class, 'show']);
});

Route::middleware(['auth:sanctum', 'role:admin,customer'])->group(function () {

    // Product
    Route::get('/products', [ProductSwaggerController::class, 'index']);
    Route::get('/products/{id}', [ProductSwaggerController::class, 'show']);
    Route::get('/products/{categoryId}', [ProductSwaggerController::class, 'getByCategory']);
    Route::get('/products/{storeId}', [ProductSwaggerController::class, 'getByStore']);

});
