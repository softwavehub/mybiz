<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Category;
use App\Models\Brand;
use App\Services\PricingEngineService;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class SellerProductController extends Controller
{
    /**
     * Display Seller's Product Catalog
     */
    public function index()
    {
        $seller = auth()->user()->seller;
        
        $products = Product::where('seller_id', $seller->id)
            ->with(['category', 'brand', 'variants'])
            ->latest()
            ->get();

        return view('seller.products.index', compact('seller', 'products'));
    }

    /**
     * Show Product Creation Form
     */
    public function create()
    {
        $seller = auth()->user()->seller;
        $categories = Category::where('is_disabled', false)->get();
        $brands = Brand::all();

        return view('seller.products.create', compact('seller', 'categories', 'brands'));
    }

    /**
     * Store New Product SKU in Pending Approval State (Section 3.2 & 4.1)
     */
    public function store(Request $request)
    {
        $seller = auth()->user()->seller;

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'category_id' => ['required', 'exists:categories,id'],
            'brand_id' => ['nullable', 'exists:brands,id'],
            'hsn_code' => ['required', 'string', 'max:20'],
            'gst_rate' => ['required', 'numeric', 'min:0', 'max:28'],
            'base_price' => ['required', 'numeric', 'min:1'],
            'shipping_zone_a' => ['required', 'numeric', 'min:0'],
            'shipping_zone_b' => ['required', 'numeric', 'min:0'],
            'shipping_zone_c' => ['required', 'numeric', 'min:0'],
            'description' => ['nullable', 'string'],
            'thumbnail_image' => ['nullable', 'string'],
            'variants' => ['required', 'array', 'min:1'],
            'variants.*.sku' => ['required', 'string', 'distinct'],
            'variants.*.attributes' => ['required', 'array'],
            'variants.*.quantity' => ['required', 'integer', 'min:0'],
            'variants.*.variant_base_price' => ['nullable', 'numeric', 'min:1'],
        ]);

        DB::transaction(function () use ($seller, $data) {
            $product = Product::create([
                'seller_id' => $seller->id,
                'category_id' => $data['category_id'],
                'brand_id' => $data['brand_id'] ?? null,
                'name' => $data['name'],
                'description' => $data['description'] ?? null,
                'hsn_code' => $data['hsn_code'],
                'gst_rate' => $data['gst_rate'],
                'base_price' => $data['base_price'],
                'shipping_zone_a' => $data['shipping_zone_a'],
                'shipping_zone_b' => $data['shipping_zone_b'],
                'shipping_zone_c' => $data['shipping_zone_c'],
                'status' => 'pending_approval', // Enforces §3.2 State Machine
                'thumbnail_image' => $data['thumbnail_image'] ?? 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=500',
            ]);

            foreach ($data['variants'] as $variantData) {
                ProductVariant::create([
                    'product_id' => $product->id,
                    'sku' => $variantData['sku'],
                    'attributes' => $variantData['attributes'],
                    'quantity' => $variantData['quantity'],
                    'variant_base_price' => $variantData['variant_base_price'] ?? null,
                    'thumbnail_image' => $data['thumbnail_image'] ?? null,
                ]);
            }
        });

        return redirect()->route('seller.products.index')->with('success', 'Product SKU submitted successfully and is under Admin Approval review.');
    }

    /**
     * Update Stock Quantity (Enforces §4.4: Bypasses Approval Workflow!)
     */
    public function updateQuantity(Request $request, ProductVariant $variant)
    {
        $seller = auth()->user()->seller;

        if ($variant->product->seller_id !== $seller->id) {
            abort(403, 'Unauthorized product modification.');
        }

        $request->validate([
            'quantity' => ['required', 'integer', 'min:0'],
        ]);

        // Direct write bypasses approval state machine per §4.4
        $variant->update(['quantity' => $request->quantity]);

        return back()->with('success', "Stock quantity for SKU [{$variant->sku}] updated instantly.");
    }
}
