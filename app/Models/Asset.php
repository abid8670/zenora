<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class Asset extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function assetCategory(): BelongsTo
    {
        return $this->belongsTo(AssetCategory::class);
    }

    public function office(): BelongsTo
    {
        return $this->belongsTo(Office::class);
    }

    public function assetAssignmentLogs(): HasMany
    {
        return $this->hasMany(AssetAssignmentLog::class);
    }

    public function latestAssignment(): HasOne
    {
        return $this->hasOne(AssetAssignmentLog::class)->latestOfMany();
    }

    public function currentAssignment(): HasOne
    {
        return $this->hasOne(AssetAssignmentLog::class)->whereNull('returned_date');
    }

    public function repairs(): HasMany
    {
        return $this->hasMany(AssetRepair::class);
    }

    public function site(): HasOneThrough
    {
        return $this->hasOneThrough(Site::class, Office::class, 'id', 'id', 'office_id', 'site_id');
    }
}
