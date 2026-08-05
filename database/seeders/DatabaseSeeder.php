<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Seller;
use App\Models\Merchant;
use App\Models\SubscriptionPlan;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\MerchantProductImport;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderLineItem;
use App\Models\RejectionReason;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed rich demo data across all tables with high-res sample images
     */
    public function run(): void
    {
        // 1. Subscription Plans
        $starterPlan = SubscriptionPlan::firstOrCreate(
            ['name' => 'Starter Free Plan'],
            [
                'price' => 0.00,
                'billing_cycle' => 'monthly',
                'product_limit' => 25,
                'custom_domain_allowed' => false,
                'escrow_accelerator_allowed' => false,
            ]
        );

        $growthPlan = SubscriptionPlan::firstOrCreate(
            ['name' => 'Growth Scale Plan'],
            [
                'price' => 1499.00,
                'billing_cycle' => 'monthly',
                'product_limit' => 250,
                'custom_domain_allowed' => true,
                'escrow_accelerator_allowed' => true,
            ]
        );

        // 2. Rejection Reasons (§4.5)
        RejectionReason::firstOrCreate(['label' => 'Incorrect HSN Tax Code for Category'], ['context_type' => 'product']);
        RejectionReason::firstOrCreate(['label' => 'Unclear/Low Quality Product Photos'], ['context_type' => 'product']);
        RejectionReason::firstOrCreate(['label' => 'Base Price Exceeds Market Threshold'], ['context_type' => 'product']);

        // 3. Super Admin User
        $admin = User::firstOrCreate(
            ['email' => 'admin@mybiz.com'],
            [
                'name' => 'Super Admin Engine',
                'phone_number' => '+91 99999 00000',
                'password' => Hash::make('Password123'),
                'role' => 'admin',
            ]
        );

        // 4. Merchant User & Store
        $merchantUser = User::firstOrCreate(
            ['email' => 'merchant@mybiz.com'],
            [
                'name' => 'Rahul Sharma',
                'phone_number' => '+91 98765 43210',
                'password' => Hash::make('Password123'),
                'role' => 'merchant',
            ]
        );

        $merchant = Merchant::firstOrCreate(
            ['user_id' => $merchantUser->id],
            [
                'subscription_plan_id' => $starterPlan->id,
                'store_name' => 'UrbanStyle Apparel',
                'subdomain' => 'urbanstyle',
                'kyc_status' => 'approved',
                'health_score' => 98,
                'health_tier' => 'excellent',
                'escrow_tier' => '15_days',
                'store_status' => 'live',
            ]
        );

        // 5. Seller User & Factory
        $sellerUser = User::firstOrCreate(
            ['email' => 'seller@mybiz.com'],
            [
                'name' => 'Vikram Textile Owner',
                'phone_number' => '+91 91234 56789',
                'password' => Hash::make('Password123'),
                'role' => 'seller',
            ]
        );

        $seller = Seller::firstOrCreate(
            ['user_id' => $sellerUser->id],
            [
                'company_name' => 'Surat Textile Mills Ltd',
                'kyc_status' => 'approved',
                'health_score' => 100,
                'health_tier' => 'excellent',
                'escrow_tier' => '15_days',
            ]
        );

        // 6. Master Categories
        $apparelCat = Category::firstOrCreate(['slug' => 'apparel-streetwear'], ['name' => 'Apparel & Streetwear', 'is_disabled' => false]);
        $bagsCat = Category::firstOrCreate(['slug' => 'bags-luggage'], ['name' => 'Bags & Luggage', 'is_disabled' => false]);
        $footwearCat = Category::firstOrCreate(['slug' => 'footwear-sneakers'], ['name' => 'Footwear & Sneakers', 'is_disabled' => false]);

        // 7. Master Brands
        $brandUrban = Brand::firstOrCreate(['slug' => 'urbancore'], ['name' => 'UrbanCore Apparel']);
        $brandApex = Brand::firstOrCreate(['slug' => 'apexgear'], ['name' => 'ApexGear Goods']);

        // 8. Products with High-Res Sample Images
        // Product 1: Approved Hoodie
        $p1 = Product::firstOrCreate(
            ['name' => 'Heavyweight Unisex Hoodie'],
            [
                'seller_id' => $seller->id,
                'category_id' => $apparelCat->id,
                'brand_id' => $brandUrban->id,
                'description' => '380 GSM fleece lined hoodie built for maximum comfort, durability, and streetwear styling.',
                'hsn_code' => '61091000',
                'gst_rate' => 5.00,
                'base_price' => 450.00,
                'shipping_zone_a' => 60.00,
                'shipping_zone_b' => 80.00,
                'shipping_zone_c' => 110.00,
                'status' => 'approved',
                'thumbnail_image' => 'https://images.unsplash.com/photo-1556905055-8f358a7a47b2?w=600',
            ]
        );

        $v1 = ProductVariant::firstOrCreate(
            ['sku' => 'HD-BLK-M'],
            [
                'product_id' => $p1->id,
                'attributes' => ['Size' => 'M', 'Color' => 'Jet Black'],
                'quantity' => 120,
                'thumbnail_image' => 'https://images.unsplash.com/photo-1556905055-8f358a7a47b2?w=600',
            ]
        );

        // Product 2: Approved T-Shirt
        $p2 = Product::firstOrCreate(
            ['name' => 'Cotton Oversized Graphic Tee'],
            [
                'seller_id' => $seller->id,
                'category_id' => $apparelCat->id,
                'brand_id' => $brandUrban->id,
                'description' => '100% combed cotton, 220 GSM streetwear drop-shoulder t-shirt with bio-wash finish.',
                'hsn_code' => '61091000',
                'gst_rate' => 5.00,
                'base_price' => 220.00,
                'shipping_zone_a' => 40.00,
                'shipping_zone_b' => 60.00,
                'shipping_zone_c' => 90.00,
                'status' => 'approved',
                'thumbnail_image' => 'https://images.unsplash.com/photo-1521572267360-ee0c2909d518?w=600',
            ]
        );

        $v2 = ProductVariant::firstOrCreate(
            ['sku' => 'TEE-WHT-L'],
            [
                'product_id' => $p2->id,
                'attributes' => ['Size' => 'L', 'Color' => 'Off White'],
                'quantity' => 200,
                'thumbnail_image' => 'https://images.unsplash.com/photo-1521572267360-ee0c2909d518?w=600',
            ]
        );

        // Product 3: Approved Backpack
        $p3 = Product::firstOrCreate(
            ['name' => 'Waterproof Canvas Backpack'],
            [
                'seller_id' => $seller->id,
                'category_id' => $bagsCat->id,
                'brand_id' => $brandApex->id,
                'description' => '25L capacity with padded 16-inch laptop compartment, YKK zippers, and secret anti-theft pocket.',
                'hsn_code' => '42021220',
                'gst_rate' => 18.00,
                'base_price' => 650.00,
                'shipping_zone_a' => 80.00,
                'shipping_zone_b' => 110.00,
                'shipping_zone_c' => 150.00,
                'status' => 'approved',
                'thumbnail_image' => 'https://images.unsplash.com/photo-1553062407-98eeb64c6a62?w=600',
            ]
        );

        $v3 = ProductVariant::firstOrCreate(
            ['sku' => 'BAG-CANVAS-OLV'],
            [
                'product_id' => $p3->id,
                'attributes' => ['Color' => 'Olive Green'],
                'quantity' => 60,
                'thumbnail_image' => 'https://images.unsplash.com/photo-1553062407-98eeb64c6a62?w=600',
            ]
        );

        // Product 4: Pending Approval Sneakers (For Admin Approval Desk)
        $p4 = Product::firstOrCreate(
            ['name' => 'Retro Minimalist Sneakers'],
            [
                'seller_id' => $seller->id,
                'category_id' => $footwearCat->id,
                'brand_id' => $brandApex->id,
                'description' => 'Handcrafted vegan leather sneakers with memory foam insoles and anti-skid rubber outsoles.',
                'hsn_code' => '64041100',
                'gst_rate' => 12.00,
                'base_price' => 890.00,
                'shipping_zone_a' => 90.00,
                'shipping_zone_b' => 120.00,
                'shipping_zone_c' => 160.00,
                'status' => 'pending_approval',
                'thumbnail_image' => 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=600',
            ]
        );

        ProductVariant::firstOrCreate(
            ['sku' => 'SNK-WHT-42'],
            [
                'product_id' => $p4->id,
                'attributes' => ['Size' => 'UK 8', 'Color' => 'White / Red'],
                'quantity' => 45,
                'thumbnail_image' => 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=600',
            ]
        );

        // 9. Merchant Product Imports
        MerchantProductImport::firstOrCreate(
            ['merchant_id' => $merchant->id, 'product_id' => $p1->id],
            [
                'imported_variant_ids' => json_encode([$v1->id]),
                'pricing_mode' => 'markup_rule',
                'markup_percentage' => 30.00,
            ]
        );

        MerchantProductImport::firstOrCreate(
            ['merchant_id' => $merchant->id, 'product_id' => $p2->id],
            [
                'imported_variant_ids' => json_encode([$v2->id]),
                'pricing_mode' => 'markup_rule',
                'markup_percentage' => 35.00,
            ]
        );

        MerchantProductImport::firstOrCreate(
            ['merchant_id' => $merchant->id, 'product_id' => $p3->id],
            [
                'imported_variant_ids' => json_encode([$v3->id]),
                'pricing_mode' => 'markup_rule',
                'markup_percentage' => 32.00,
            ]
        );

        // 10. Sample Customers & Orders
        $customer = Customer::firstOrCreate(
            ['merchant_id' => $merchant->id, 'phone_number' => '+91 98765 11111'],
            ['name' => 'Ananya Verma', 'email' => 'ananya@gmail.com', 'address' => '402 Sunrise Heights, Bandra West, Mumbai', 'pincode' => '400050']
        );

        $order1 = Order::firstOrCreate(
            ['order_number' => 'ORD-9821'],
            [
                'merchant_id' => $merchant->id,
                'customer_id' => $customer->id,
                'seller_id' => $seller->id,
                'total_amount' => 1498.00,
                'seller_amount' => 1020.00,
                'platform_commission' => 149.80,
                'pg_fee' => 29.96,
                'merchant_profit' => 298.24,
                'escrow_status' => 'holding',
                'order_status' => 'placed',
                'shipping_address' => '402 Sunrise Heights, Bandra West, Mumbai',
                'pincode' => '400050',
                'cancellation_window_closes_at' => now()->addHours(20),
            ]
        );

        OrderLineItem::firstOrCreate(
            ['order_id' => $order1->id, 'sku' => 'HD-BLK-M'],
            [
                'product_id' => $p1->id,
                'product_variant_id' => $v1->id,
                'product_name' => $p1->name,
                'variant_attributes' => json_encode(['Size' => 'M', 'Color' => 'Jet Black']),
                'quantity' => 2,
                'unit_base_price' => 450.00,
                'unit_retail_price' => 749.00,
                'total_price' => 1498.00,
            ]
        );
    }
}
