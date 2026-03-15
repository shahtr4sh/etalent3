<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements FilamentUser
{
    use HasFactory, Notifiable, HasRoles;

    protected $guard_name = 'web';

    public function canAccessPanel(Panel $panel): bool
    {
        // Untuk panel 'staff', hanya user biasa boleh access
        if ($panel->getId() === 'staff') {
            return true;
        }

        // Untuk panel 'admin', super admin dan pelulus boleh access
        if ($panel->getId() === 'admin') {
            return $this->hasRole(['super_admin', 'penyemak']);
        }
    return false;
    }

    public function pemohon()
    {
        // pemohon PK staff_id, users ada staff_id
        return $this->hasOne(\App\Models\Pemohon::class, 'staff_id', 'staff_id');
    }

    public function promotionApplications()
    {
        // staff_id based
        return $this->hasMany(\App\Models\PromotionApplication::class, 'staff_id', 'staff_id');
    }

    protected $fillable = [
        'name',
        'email',
        'staff_id',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
