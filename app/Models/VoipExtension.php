<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VoipExtension extends Model
{
    use HasFactory;

    protected $fillable = [
        'extension_number',
        'user_id',
        'display_name',
        'office_id',
        'status',
        'remarks',
    ];

    /**
     * Get the user that owns the extension.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the office where the extension is located.
     */
    public function office(): BelongsTo
    {
        return $this->belongsTo(Office::class);
    }
}
