<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// 1. Customer Storefront (White-Labeled Tenant View)
Route::get('/', function () {
    return view('storefront.index');
});

// 2. Super Admin Dashboard
Route::get('/admin/dashboard', function () {
    return view('admin.dashboard');
});

// 3. Merchant Panel Dashboard
Route::get('/merchant/dashboard', function () {
    return view('merchant.dashboard');
});

// 4. Seller Panel Dashboard
Route::get('/seller/dashboard', function () {
    return view('seller.dashboard');
});
