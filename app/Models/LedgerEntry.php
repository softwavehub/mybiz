<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Exception;

class LedgerEntry extends Model
{
    use HasFactory;

    public $timestamps = false; // Uses created_at only

    protected $fillable = [
        'order_id',
        'account_type',
        'entry_type',
        'amount',
        'reference_id',
        'description',
        'created_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'created_at' => 'datetime',
    ];

    /**
     * Enforce Rule #3: Append-Only Ledger.
     * Throw exception if any code attempts to update or delete a ledger entry.
     */
    protected static function booted()
    {
        static::updating(function ($model) {
            throw new Exception("Financial Ledger Integrity Violation: Ledger entries are append-only and cannot be updated.");
        });

        static::deleting(function ($model) {
            throw new Exception("Financial Ledger Integrity Violation: Ledger entries are append-only and cannot be deleted.");
        });
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
