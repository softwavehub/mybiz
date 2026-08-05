<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dispute extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'customer_id',
        'merchant_id',
        'seller_id',
        'claim_type',
        'proof_photos',
        'unboxing_video_url',
        'status',
        'rejection_reason_id',
    ];

    protected $casts = [
        'proof_photos' => 'array',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function merchant()
    {
        return $this->belongsTo(Merchant::class);
    }

    public function seller()
    {
        return $this->belongsTo(Seller::class);
    }

    public function rejectionReason()
    {
        return $this->belongsTo(RejectionReason::class);
    }
}
