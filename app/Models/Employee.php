<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Employee extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($employee) {
            // Get the last employee ID
            $lastEmployee = Employee::orderBy('id', 'desc')->first();
            $lastId = $lastEmployee ? $lastEmployee->id : 0;

            // Generate the new employee_id
            $employee->employee_id = 'EMP-' . str_pad($lastId + 1, 3, '0', STR_PAD_LEFT);
        });
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function office(): BelongsTo
    {
        return $this->belongsTo(Office::class);
    }

    public function assetAssignmentLogs(): HasMany
    {
        return $this->hasMany(AssetAssignmentLog::class);
    }
}
