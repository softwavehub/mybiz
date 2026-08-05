<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Merchant extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'subscription_plan_id',
        'store_name',
        'subdomain',
        'custom_domain',
        'gstin',
        'pan',
        'bank_account_number',
        'bank_ifsc',
        'kyc_status',
        'health_score',
        'health_tier',
        'escrow_tier',
        'tenure_days',
        'rto_reserve_balance',
        'store_status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function subscriptionPlan()
    {
        return $this->belongsTo(SubscriptionPlan::class);
    }

    public function imports()
    {
        return $this->hasMany(MerchantProductImport::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function customers()
    {
        return $this->hasMany(Customer::class);
    }
}
