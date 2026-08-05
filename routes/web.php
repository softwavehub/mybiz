<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| Web Routes - mybiz Platform
|--------------------------------------------------------------------------
*/

// 1. Customer Storefront (White-Labeled Tenant View)
Route::get('/', function () {
    return view('storefront.index');
})->name('storefront.index');

// 2. Authentication Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// 3. Super Admin Engine (Protected: Admin Only)
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');
});

// 4. Merchant Panel (Protected: Merchant Only)
Route::middleware(['auth', 'role:merchant'])->prefix('merchant')->group(function () {
    Route::get('/dashboard', function () {
        return view('merchant.dashboard');
    })->name('merchant.dashboard');
});

// 5. Seller Panel (Protected: Seller Only)
Route::middleware(['auth', 'role:seller'])->prefix('seller')->group(function () {
    Route::get('/dashboard', function () {
        return view('seller.dashboard');
    })->name('seller.dashboard');
});
