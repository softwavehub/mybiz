<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeatureFlag extends Model
{
    use HasFactory;

    protected $fillable = [
        'scope',
        'scope_id',
        'feature_key',
        'enabled',
        'reason',
        'updated_by',
    ];

    protected $casts = [
        'enabled' => 'boolean',
    ];

    /**
     * Helper to check if a feature flag is enabled for a scope
     */
    public static function isEnabled(string $featureKey, string $scope = 'platform', ?int $scopeId = null): bool
    {
        $flag = static::where('feature_key', $featureKey)
            ->where('scope', $scope)
            ->where('scope_id', $scopeId)
            ->first();

        if (!$flag) {
            // Default platform fallback
            $platformFlag = static::where('feature_key', $featureKey)
                ->where('scope', 'platform')
                ->first();
            return $platformFlag ? $platformFlag->enabled : true;
        }

        return $flag->enabled;
    }
}
