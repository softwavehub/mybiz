<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RejectionReason extends Model
{
    use HasFactory;

    protected $fillable = ['context_type', 'label', 'usage_count'];

    public function disputes()
    {
        return $this->hasMany(Dispute::class);
    }
}
