<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SellerProductController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AdminApprovalController;
use App\Http\Controllers\MerchantCatalogController;
use App\Http\Controllers\CheckoutController;

/*
|--------------------------------------------------------------------------
| Web Routes - mybiz B2B2C Platform Engine
|--------------------------------------------------------------------------
*/

// 1. Customer Storefront & Direct Checkout
Route::get('/', function () {
    return view('storefront.index');
})->name('storefront.index');

Route::post('/orders/place', [CheckoutController::class, 'placeOrder'])->name('orders.place');
Route::get('/orders/track/{orderNumber}', [CheckoutController::class, 'trackOrder'])->name('orders.track');

// 2. Authentication Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// 3. Super Admin Engine (Protected: Admin Only)
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    Route::post('/sellers/{seller}/toggle-status', [AdminDashboardController::class, 'toggleSellerStatus'])->name('admin.sellers.toggle-status');
    Route::post('/sellers/{seller}/escrow-tier', [AdminDashboardController::class, 'updateEscrowTier'])->name('admin.sellers.escrow-tier');
    Route::post('/orders/{order}/release-escrow', [AdminDashboardController::class, 'releaseEscrow'])->name('admin.orders.release-escrow');
    Route::post('/categories/{category}/toggle', [AdminDashboardController::class, 'toggleCategory'])->name('admin.categories.toggle');
    Route::post('/feature-flags/{flag}/toggle', [AdminDashboardController::class, 'toggleFeatureFlag'])->name('admin.feature-flags.toggle');

    Route::get('/approvals', [AdminApprovalController::class, 'index'])->name('admin.approvals.index');
    Route::post('/products/{product}/approve', [AdminApprovalController::class, 'approveProduct'])->name('admin.products.approve');
    Route::post('/products/{product}/reject', [AdminApprovalController::class, 'rejectProduct'])->name('admin.products.reject');
    Route::post('/kyc/{type}/{id}/approve', [AdminApprovalController::class, 'approveKyc'])->name('admin.kyc.approve');
});

// 4. Merchant Operations Desk (Protected: Merchant Only)
Route::middleware(['auth', 'role:merchant'])->prefix('merchant')->group(function () {
    Route::get('/dashboard', function () {
        return view('merchant.dashboard');
    })->name('merchant.dashboard');

    Route::get('/catalog', [MerchantCatalogController::class, 'index'])->name('merchant.catalog.index');
    Route::post('/catalog/{product}/import', [MerchantCatalogController::class, 'import'])->name('merchant.catalog.import');
});

// 5. Seller & Supplier Hub (Protected: Seller Only)
Route::middleware(['auth', 'role:seller'])->prefix('seller')->group(function () {
    Route::get('/dashboard', function () {
        return view('seller.dashboard');
    })->name('seller.dashboard');

    Route::get('/products', [SellerProductController::class, 'index'])->name('seller.products.index');
    Route::get('/products/create', [SellerProductController::class, 'create'])->name('seller.products.create');
    Route::post('/products', [SellerProductController::class, 'store'])->name('seller.products.store');
    Route::patch('/variants/{variant}/quantity', [SellerProductController::class, 'updateQuantity'])->name('seller.variants.quantity');
});
