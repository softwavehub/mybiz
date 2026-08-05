<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Seller;
use App\Models\Merchant;
use App\Models\SubscriptionPlan;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed initial demo users and roles for mybiz
     */
    public function run(): void
    {
        // 1. Subscription Plan
        $starterPlan = SubscriptionPlan::firstOrCreate(
            ['name' => 'Starter Free Plan'],
            ['price' => 0.00, 'product_limit' => 25, 'billing_cycle' => 'monthly']
        );

        // 2. Super Admin User
        $admin = User::firstOrCreate(
            ['email' => 'admin@mybiz.com'],
            [
                'name' => 'Super Admin',
                'phone_number' => '+91 99999 00000',
                'password' => Hash::make('Password123'),
                'role' => 'admin',
            ]
        );

        // 3. Merchant User
        $merchantUser = User::firstOrCreate(
            ['email' => 'merchant@mybiz.com'],
            [
                'name' => 'Rahul Sharma',
                'phone_number' => '+91 98765 43210',
                'password' => Hash::make('Password123'),
                'role' => 'merchant',
            ]
        );

        Merchant::firstOrCreate(
            ['user_id' => $merchantUser->id],
            [
                'subscription_plan_id' => $starterPlan->id,
                'store_name' => 'UrbanStyle Apparel',
                'subdomain' => 'urbanstyle',
                'kyc_status' => 'approved',
                'health_score' => 100,
                'health_tier' => 'excellent',
                'escrow_tier' => '15_days',
                'store_status' => 'live',
            ]
        );

        // 4. Seller User
        $sellerUser = User::firstOrCreate(
            ['email' => 'seller@mybiz.com'],
            [
                'name' => 'Vikram Textile Owner',
                'phone_number' => '+91 91234 56789',
                'password' => Hash::make('Password123'),
                'role' => 'seller',
            ]
        );

        Seller::firstOrCreate(
            ['user_id' => $sellerUser->id],
            [
                'company_name' => 'Surat Textile Mills Ltd',
                'kyc_status' => 'approved',
                'health_score' => 100,
                'health_tier' => 'excellent',
                'escrow_tier' => '15_days',
            ]
        );
    }
}
