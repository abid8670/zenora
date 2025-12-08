<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Isp extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'office_id',
        'name',
        'provider',
        'speed',
        'circuit_id',
        'connection_type',
        'location',
        'static_ip',
        'status',
        'management_url',
        'username',
        'password',
        'account_number',
        'monthly_cost',
        'billing_date',
        'installation_date',
        'remarks',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'billing_date' => 'date',
        'installation_date' => 'date',
        'monthly_cost' => 'decimal:2',
        'password' => 'encrypted',
    ];

    /**
     * Get the office that this ISP connection belongs to.
     */
    public function office(): BelongsTo
    {
        return $this->belongsTo(Office::class);
    }
}
