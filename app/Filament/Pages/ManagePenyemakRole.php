<?php

namespace App\Filament\Pages;

use App\Models\User;
use Filament\Pages\Page;
use Filament\Notifications\Notification;
use Spatie\Permission\Models\Role;

class ManagePenyemakRole extends Page
{
    protected static string|null|\BackedEnum $navigationIcon = 'heroicon-o-shield-check';

    protected static ?string $navigationLabel = 'Tambah Penyemak';

    protected static ?string $title = 'Tambah Pengguna sebagai Penyemak';

    protected string $view = 'filament.pages.manage-penyemak-role';

    public $staff_id = '';

    public static function canAccess(): bool
    {
        // Check if user has super_admin role
        return auth()->user()?->hasRole('super_admin') ?? false;
    }


    public function assignRole()
    {
        if (empty($this->staff_id)) {
            Notification::make()->title('Error')->body('Sila masukkan Staff ID')->danger()->send();
            return;
        }

        // 1. Cari dalam users dulu
        $user = User::where('staff_id', $this->staff_id)->first();

        // 2. Kalau takde, cari dalam pemohon
        if (!$user) {
            $pemohon = \App\Models\Pemohon::where('staff_id', $this->staff_id)->first();

            if ($pemohon) {
                // Auto-create user dari data pemohon
                $user = User::create([
                    'name' => $pemohon->nama,
                    'email' => $pemohon->emel_rasmi,
                    'staff_id' => $pemohon->staff_id,
                    'password' => bcrypt($pemohon->staff_id), // password default = staff_id
                ]);

                Notification::make()
                    ->title('Akaun automatik dicipta')
                    ->body("Akaun untuk {$pemohon->nama} telah dicipta")
                    ->warning()
                    ->send();
            } else {
                Notification::make()
                    ->title('Error')
                    ->body('Staff ID ' . $this->staff_id . ' tidak dijumpai dalam sistem')
                    ->danger()
                    ->send();
                return;
            }
        }

        // 3. Assign role
        $user->assignRole('penyemak');

        $this->staff_id = '';

        Notification::make()
            ->title('Berjaya!')
            ->body("{$user->name} kini adalah PENYEMAK")
            ->success()
            ->send();
    }
}
