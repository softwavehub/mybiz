<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubscriptionPlan extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'price',
        'billing_cycle',
        'product_limit',
        'custom_domain_allowed',
        'escrow_accelerator_allowed',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'custom_domain_allowed' => 'boolean',
        'escrow_accelerator_allowed' => 'boolean',
    ];

    public function merchants()
    {
        return $this->hasMany(Merchant::class);
    }
}
