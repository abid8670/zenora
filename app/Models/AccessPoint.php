<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class AccessPoint extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'office_id',
        'name',
        'ip_address',
        'management_url',
        'username',
        'password',
        'notes',
    ];

    public function office(): BelongsTo
    {
        return $this->belongsTo(Office::class);
    }

    public function wifiSsids(): HasMany
    {
        return $this->hasMany(WifiSsid::class);
    }
}
