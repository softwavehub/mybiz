<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\MerchantProductImport;
use App\Services\PricingEngineService;
use App\Models\FeatureFlag;

class MerchantCatalogController extends Controller
{
    /**
     * View Approved Master Supplier Catalog for 1-Click Store Import
     */
    public function index()
    {
        $merchant = auth()->user()->merchant;

        $approvedProducts = Product::where('status', 'approved')
            ->whereHas('category', function ($q) {
                $q->where('is_disabled', false);
            })
            ->with(['seller', 'category', 'variants'])
            ->latest()
            ->get();

        $importedProductIds = MerchantProductImport::where('merchant_id', $merchant->id)
            ->pluck('product_id')
            ->toArray();

        return view('merchant.catalog.index', compact('merchant', 'approvedProducts', 'importedProductIds'));
    }

    /**
     * 1-Click Import Product with 8 Server-Side Validation Checks (§4.3)
     */
    public function import(Request $request, Product $product)
    {
        $merchant = auth()->user()->merchant;

        $request->validate([
            'markup_percent' => ['required', 'numeric', 'min:0'],
        ]);

        // CHECK 1: Product Status
        if ($product->status !== 'approved') {
            return back()->with('error', 'Import Failed: Product is not approved by Admin.');
        }

        // CHECK 2: Category Disabled State
        if ($product->category && $product->category->is_disabled) {
            return back()->with('error', 'Import Failed: Product category is disabled.');
        }

        // CHECK 3: Feature Flag Check (§0.7)
        if (!FeatureFlag::isEnabled('catalog_import', 'merchant', $merchant->id)) {
            return back()->with('error', 'Import Failed: Catalog import feature is disabled for your store.');
        }

        // CHECK 4: Seller KYC State
        if ($product->seller && $product->seller->kyc_status !== 'approved') {
            return back()->with('error', 'Import Failed: Supplier KYC is not active.');
        }

        // CHECK 5: Merchant Product Limit Check (§4.7)
        $currentImportCount = MerchantProductImport::where('merchant_id', $merchant->id)->count();
        $planLimit = $merchant->subscriptionPlan ? $merchant->subscriptionPlan->product_limit : 25;
        if ($currentImportCount >= $planLimit) {
            return back()->with('error', "Import Failed: You have reached your Subscription Plan limit of {$planLimit} imported products.");
        }

        // Price Floor Calculation (§4.1)
        $pricing = PricingEngineService::calculatePriceFloor(
            $product->base_price,
            $product->shipping_zone_a,
            10.0, // 10% platform commission
            2.0   // 2% PG fee
        );

        $retailPrice = PricingEngineService::calculateMerchantRetailPrice(
            $pricing['absolute_price_floor'],
            $request->markup_percent
        );

        // CHECK 6: Price Floor Validation
        if ($retailPrice < $pricing['absolute_price_floor']) {
            return back()->with('error', "Import Failed: Retail Price (₹{$retailPrice}) cannot be below Absolute Price Floor (₹{$pricing['absolute_price_floor']}).");
        }

        MerchantProductImport::updateOrCreate(
            [
                'merchant_id' => $merchant->id,
                'product_id' => $product->id,
            ],
            [
                'custom_title' => $product->name,
                'markup_percentage' => $request->markup_percent,
                'retail_price' => $retailPrice,
                'is_active' => true,
            ]
        );

        return back()->with('success', "Product [{$product->name}] imported to your store at ₹{$retailPrice} (₹" . round($retailPrice - $pricing['absolute_price_floor'], 2) . " profit/sale).");
    }
}
