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
        'unit_base_price' => 'decimal:2',
        'unit_retail_price' => 'decimal:2',
        'total_price' => 'decimal:2',
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
