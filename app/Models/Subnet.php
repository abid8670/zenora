<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subnet extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'subnet_address',
        'office_id',
        'description',
        'gateway',
        'vlan_id',
        'notes',
    ];

    /**
     * Get the office that the subnet belongs to.
     */
    public function office(): BelongsTo
    {
        return $this->belongsTo(Office::class);
    }
}
