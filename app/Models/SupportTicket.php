<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SupportTicket extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'office_id',
        'support_type_id',
        'local_ip',
        'title',
        'description',
        'status',
        'remarks',
    ];

    public function office(): BelongsTo
    {
        return $this->belongsTo(Office::class);
    }

    public function supportType(): BelongsTo
    {
        return $this->belongsTo(SupportType::class);
    }
}
