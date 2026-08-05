<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderLineItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'product_variant_id',
        'sku',
        'product_name',
        'variant_attributes',
        'quantity',
        'unit_base_price',
        'unit_retail_price',
        'total_price',
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

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
