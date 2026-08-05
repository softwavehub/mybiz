<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderLineItem;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Merchant;
use App\Models\Customer;
use App\Services\LedgerService;
use App\Services\PricingEngineService;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    /**
     * Process Customer Direct Checkout & Execute Double-Entry Ledger Transactions
     */
    public function placeOrder(Request $request)
    {
        $data = $request->validate([
            'merchant_id' => ['required', 'exists:merchants,id'],
            'product_id' => ['required', 'exists:products,id'],
            'variant_id' => ['required', 'exists:product_variants,id'],
            'customer_name' => ['required', 'string', 'max:255'],
            'customer_phone' => ['required', 'string', 'max:20'],
            'customer_email' => ['required', 'email', 'max:255'],
            'shipping_address' => ['required', 'string'],
            'pincode' => ['required', 'string', 'max:10'],
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        $merchant = Merchant::findOrFail($data['merchant_id']);
        $product = Product::findOrFail($data['product_id']);
        $variant = ProductVariant::findOrFail($data['variant_id']);

        if ($variant->quantity < $data['quantity']) {
            return back()->with('error', 'Item is out of stock.');
        }

        return DB::transaction(function () use ($merchant, $product, $variant, $data) {
            // 1. Create or Find Tenant Customer
            $customer = Customer::firstOrCreate(
                [
                    'merchant_id' => $merchant->id,
                    'phone_number' => $data['customer_phone'],
                ],
                [
                    'name' => $data['customer_name'],
                    'email' => $data['customer_email'],
                    'address' => $data['shipping_address'],
                    'pincode' => $data['pincode'],
                ]
            );

            // 2. Financial Pricing & Ledger Amounts (§4.1)
            $pricing = PricingEngineService::calculatePriceFloor(
                $product->base_price,
                $product->shipping_zone_a,
                10.0,
                2.0
            );

            $unitRetailPrice = max(749.00, $pricing['absolute_price_floor']);
            $totalAmount = $unitRetailPrice * $data['quantity'];
            $sellerAmount = ($product->base_price + $product->shipping_zone_a) * $data['quantity'];
            $platformCommission = $pricing['platform_commission'] * $data['quantity'];
            $pgFee = $pricing['pg_fee'] * $data['quantity'];
            $merchantProfit = ($unitRetailPrice - $pricing['absolute_price_floor']) * $data['quantity'];

            // 3. Create Immutable Order Record
            $order = Order::create([
                'order_number' => 'ORD-' . strtoupper(Str::random(8)),
                'merchant_id' => $merchant->id,
                'customer_id' => $customer->id,
                'seller_id' => $product->seller_id,
                'total_amount' => $totalAmount,
                'seller_amount' => $sellerAmount,
                'platform_commission' => $platformCommission,
                'pg_fee' => $pgFee,
                'merchant_profit' => $merchantProfit,
                'escrow_status' => 'holding',
                'order_status' => 'placed', // §3.1 State Machine
                'shipping_address' => $data['shipping_address'],
                'pincode' => $data['pincode'],
                'cancellation_window_closes_at' => now()->addHours(24),
            ]);

            // 4. Create Snapshot Order Line Item
            OrderLineItem::create([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'product_variant_id' => $variant->id,
                'sku' => $variant->sku,
                'product_name' => $product->name,
                'variant_attributes' => json_encode($variant->attributes),
                'quantity' => $data['quantity'],
                'unit_base_price' => $product->base_price,
                'unit_retail_price' => $unitRetailPrice,
                'total_price' => $totalAmount,
            ]);

            // 5. Decrement Inventory
            $variant->decrement('quantity', $data['quantity']);

            // 6. Record Double-Entry Immutable Ledger Transaction (§0.3)
            LedgerService::recordOrderPayment(
                $order->id,
                $order->merchant_id,
                $order->seller_id,
                $totalAmount,
                $sellerAmount,
                $platformCommission,
                $merchantProfit
            );

            return redirect()->route('orders.track', $order->order_number)
                ->with('success', "Order placed successfully! Order Number: {$order->order_number}");
        });
    }

    /**
     * Unified Order Tracking Hub (§5.5)
     */
    public function trackOrder(string $orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)
            ->with(['merchant', 'customer', 'seller', 'lineItems'])
            ->firstOrFail();

        return view('orders.track', compact('order'));
    }
}
