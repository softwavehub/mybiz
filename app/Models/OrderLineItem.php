<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderLineItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_variant_id',
        'product_name',
        'hsn_code',
        'gst_rate',
        'base_price',
        'shipping_fee',
        'platform_commission',
        'pg_fee',
        'price_floor',
        'retail_price',
        'merchant_profit',
        'quantity',
    ];

    protected $casts = [
        'base_price' => 'decimal:2',
        'shipping_fee' => 'decimal:2',
        'platform_commission' => 'decimal:2',
        'pg_fee' => 'decimal:2',
        'price_floor' => 'decimal:2',
        'retail_price' => 'decimal:2',
        'merchant_profit' => 'decimal:2',
        'gst_rate' => 'decimal:2',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function productVariant()
    {
        return $this->belongsTo(ProductVariant::class);
    }
}
