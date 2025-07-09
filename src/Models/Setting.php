<?php

namespace Eugenefvdm\MultiTenancyPWA\Models;

use Eugenefvdm\MultiTenancyPWA\Traits\HasTenantRelationship;
use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasTenantRelationship;

    protected $fillable = [
        'tenant_id',
        'key',
        'value',
    ];

    public static function get(string $key, $tenantId): mixed
    {
        if (! $tenantId) {
            $tenantId = Filament::getTenant()->id;
        }

        $setting = static::where('tenant_id', $tenantId)
            ->where('key', $key)->first();

        return $setting?->value;
    }
}
