<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'seller_id',
        'category_id',
        'brand_id',
        'name',
        'description',
        'hsn_code',
        'gst_rate',
        'unit_of_measurement',
        'base_price',
        'shipping_zone_a',
        'shipping_zone_b',
        'shipping_zone_c',
        'status',
        'thumbnail_image',
        'gallery_images',
    ];

    protected $casts = [
        'gallery_images' => 'array',
        'base_price' => 'decimal:2',
        'gst_rate' => 'decimal:2',
    ];

    public function seller()
    {
        return $this->belongsTo(Seller::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function revisions()
    {
        return $this->hasMany(ProductRevision::class);
    }
}
