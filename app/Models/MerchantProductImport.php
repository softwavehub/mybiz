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
        'imported_variant_ids',
        'pricing_mode',
        'fixed_price',
        'markup_percentage',
        'collection_tags',
    ];

    protected $casts = [
        'imported_variant_ids' => 'array',
        'collection_tags' => 'array',
        'fixed_price' => 'decimal:2',
        'markup_percentage' => 'decimal:2',
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
