<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Crypt;

class Hosting extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'status',
        'project_id',
        'office_id',
        'provider',
        'plan_name',
        'server_ip',
        'login_url',
        'username',
        'password',
        'registration_date',
        'expiry_date',
        'nameservers',
        'dns_management_url',
        'backup_info',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'nameservers' => 'array',
        'registration_date' => 'date',
        'expiry_date' => 'date',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $user = Auth::user();
            if ($user) {
                $model->created_by = $user->id;
                $model->updated_by = $user->id;
            }
        });

        static::updating(function ($model) {
            $user = Auth::user();
            if ($user) {
                $model->updated_by = $user->id;
            }
        });
    }

    protected function password(): Attribute
    {
        return Attribute::make(
            get: fn (?string $value) => $value ? Crypt::decryptString($value) : null,
            set: fn (?string $value) => $value ? Crypt::encryptString($value) : null,
        );
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function office()
    {
        return $this->belongsTo(Office::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
