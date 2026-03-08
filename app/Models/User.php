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

    /**
     * Filament panel access control
     */
    public function canAccessPanel(Panel $panel): bool
    {
        if ($panel->getId() !== 'admin') {
            return true;
        }

        // Restrict admin panel kepada role tertentu sahaja
        return $this->hasAnyRole([
            'super_admin',
            'admin sistem',
            'urusetia',
            'pengurusan atasan',
            'penyemak',
            'pelulus'
        ]);
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
