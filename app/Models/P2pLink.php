<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Crypt;

class P2pLink extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'link_type',
        'status',
        'office_a_id',
        'office_b_id',
        'device_url',
        'username',
        'password',
        'vpn_server_ip',
        'vpn_user',
        'vpn_password',
        'remarks',
    ];

    protected $casts = [
        'password' => 'encrypted',
        'vpn_password' => 'encrypted',
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
