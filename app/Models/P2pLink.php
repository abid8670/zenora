<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class P2pLink extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'link_type',
        'status',
        'link_speed',
        'ownership',
        'office_a_id',
        'office_b_id',
        'device_a_type',
        'device_a_mode',
        'device_a_wan_ip',
        'device_a_url',
        'device_a_username',
        'device_a_password',
        'device_b_type',
        'device_b_mode',
        'device_b_wan_ip',
        'device_b_url',
        'device_b_username',
        'device_b_password',
        'remarks',
    ];

    protected $casts = [
        'device_a_password' => 'encrypted',
        'device_b_password' => 'encrypted',
    ];

    public function officeA(): BelongsTo
    {
        return $this->belongsTo(Office::class, 'office_a_id');
    }

    public function officeB(): BelongsTo
    {
        return $this->belongsTo(Office::class, 'office_b_id');
    }
}
