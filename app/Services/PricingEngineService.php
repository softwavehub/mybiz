<?php

namespace App\Services;

use Exception;

class PricingEngineService
{
    /**
     * Platform Commission Rate (10%)
     */
    public const PLATFORM_COMMISSION_RATE = 0.10;

    /**
     * Payment Gateway Fee Buffer Rate (2%)
     */
    public const PG_FEE_RATE = 0.02;

    /**
     * Calculate Absolute Price Floor:
     * Subtotal = Base Price + Shipping Fee
     * Platform Commission = 10% of Subtotal
     * Subtotal after Commission = Subtotal + Platform Commission
     * Price Floor = Subtotal after Commission / (1 - 0.02 PG Fee)
     */
    public function calculatePriceFloor(float $basePrice, float $shippingFee): float
    {
        $subtotal = $basePrice + $shippingFee;
        $platformCommission = $subtotal * self::PLATFORM_COMMISSION_RATE;
        $subtotalWithCommission = $subtotal + $platformCommission;
        
        $priceFloor = $subtotalWithCommission / (1.0 - self::PG_FEE_RATE);
        
        return round($priceFloor, 2);
    }

    /**
     * Validate whether a Merchant's selling price exceeds the Absolute Price Floor.
     */
    public function validateRetailPrice(float $basePrice, float $shippingFee, float $retailPrice): bool
    {
        $priceFloor = $this->calculatePriceFloor($basePrice, $shippingFee);
        return $retailPrice > $priceFloor;
    }

    /**
     * Calculate Merchant Net Profit:
     * Merchant Profit = Selling Price - Absolute Price Floor
     */
    public function calculateMerchantProfit(float $basePrice, float $shippingFee, float $retailPrice): float
    {
        $priceFloor = $this->calculatePriceFloor($basePrice, $shippingFee);
        
        if ($retailPrice <= $priceFloor) {
            throw new Exception("Retail price (₹{$retailPrice}) must be greater than the absolute price floor of ₹{$priceFloor}.");
        }

        return round($retailPrice - $priceFloor, 2);
    }
}
