<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Models\Merchant;
use App\Models\Product;
use App\Services\PricingEngineService;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// 1. Customer Storefront (White-Labeled Tenant View)
Route::get('/', function () {
    $pricingEngine = new PricingEngineService();

    $merchant = [
        'store_name' => 'UrbanStyle Apparel',
        'subdomain' => 'urbanstyle',
        'custom_domain' => null,
    ];

    $products = [
        [
            'id' => 1,
            'name' => 'Heavyweight Unisex Hoodie',
            'description' => '380 GSM fleece lined hoodie built for maximum comfort and longevity.',
            'thumbnail_image' => 'https://images.unsplash.com/photo-1556905055-8f358a7a47b2?w=500',
            'retail_price' => 749.00,
        ],
        [
            'id' => 2,
            'name' => 'Cotton Oversized Graphic Tee',
            'description' => '100% combed cotton, 220 GSM streetwear drop-shoulder t-shirt.',
            'thumbnail_image' => 'https://images.unsplash.com/photo-1521572267360-ee0c2909d518?w=500',
            'retail_price' => 399.00,
        ],
        [
            'id' => 3,
            'name' => 'Waterproof Canvas Backpack',
            'description' => '25L capacity with padded 16-inch laptop compartment and secret anti-theft pocket.',
            'thumbnail_image' => 'https://images.unsplash.com/photo-1553062407-98eeb64c6a62?w=500',
            'retail_price' => 1099.00,
        ]
    ];

    return Inertia::render('Storefront/Index', [
        'merchant' => $merchant,
        'products' => $products,
    ]);
});

// 2. Super Admin Dashboard
Route::get('/admin/dashboard', function () {
    return Inertia::render('Admin/Dashboard', [
        'metrics' => [
            'gmv' => '1,24,500',
            'commission' => '12,450',
            'escrow' => '45,200',
            'disputes' => 2,
        ]
    ]);
});

// 3. Merchant Panel Dashboard
Route::get('/merchant/dashboard', function () {
    return Inertia::render('Merchant/Dashboard', [
        'merchant' => [
            'store_name' => 'UrbanStyle Apparel',
            'subdomain' => 'urbanstyle',
        ],
    ]);
});

// 4. Seller Panel Dashboard
Route::get('/seller/dashboard', function () {
    return Inertia::render('Seller/Dashboard', [
        'seller' => [
            'company_name' => 'Surat Textile Mills Ltd',
        ],
    ]);
});
