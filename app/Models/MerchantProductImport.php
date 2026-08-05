<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MerchantProductImport extends Model
{
    use HasFactory;

    protected $fillable = [
        'merchant_id',
        'product_id',
        'custom_title',
        'markup_percentage',
        'retail_price',
        'is_active',
        'grace_period_expires_at',
    ];

    protected $casts = [
        'markup_percentage' => 'decimal:2',
        'retail_price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function merchant()
    {
        return $this->belongsTo(Merchant::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
