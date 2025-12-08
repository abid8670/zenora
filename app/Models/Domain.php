<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class Domain extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'status',
        'project_id',
        'office_id',
        'registrar',
        'registration_date',
        'expiry_date',
        'owner_name',
        'owner_email',
        'panel_url',
        'panel_username',
        'panel_password',
        'nameservers',
        'primary_hosting_id',
        'backup_hosting_id',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'registration_date' => 'date',
        'expiry_date' => 'date',
        'nameservers' => 'array',
    ];

    protected function panelPassword(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value ? Crypt::decryptString($value) : null,
            set: fn ($value) => $value ? Crypt::encryptString($value) : null,
        );
    }

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

    public function primaryHosting()
    {
        return $this->belongsTo(Hosting::class, 'primary_hosting_id');
    }

    public function backupHosting()
    {
        return $this->belongsTo(Hosting::class, 'backup_hosting_id');
    }
}
