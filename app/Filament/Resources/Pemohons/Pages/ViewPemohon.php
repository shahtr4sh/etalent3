<?php

namespace App\Filament\Resources\Pemohons\Pages;

use App\Filament\Resources\Pemohons\PemohonResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Notifications\Notification;
use Filament\Forms;
use Spatie\Permission\Models\Role;
use App\Models\User;

class ViewPemohon extends ViewRecord
{
    protected static string $resource = PemohonResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make()->label('Edit Staf'),

            // ✅ Action Tetapkan Role
            Actions\Action::make('assignRole')
                ->label('Tetapkan Role')
                ->icon('heroicon-m-user-plus')
                ->color('info')
                ->form([
                    Forms\Components\Select::make('role')
                        ->label('Pilih Role')
                        ->options(fn () => Role::query()
                            ->where('guard_name', 'web')
                            ->orderBy('name')
                            ->pluck('name', 'name')
                            ->toArray()
                        )
                        ->searchable()
                        ->required(),
                ])
                ->action(function (array $data) {
                    $pemohon = $this->record;

                    // ✔️ Cari User berdasarkan 'staff_id' (ubah jika anda guna padanan lain, e.g. email)
                    $user = User::where('staff_id', $pemohon->staff_id)->first();

                    if (!$user) {
                        Notification::make()
                            ->title('Pengguna tidak dijumpai')
                            ->body('Rekod User untuk staf ini tiada. Pastikan User wujud & ada staff_id yang sama.')
                            ->danger()
                            ->send();
                        return;
                    }

                    // Tetapkan role tunggal (guna syncRoles untuk overwrite penuh)
                    $user->syncRoles([$data['role']]);

                    Notification::make()
                        ->title('Role dikemaskini')
                        ->body("{$pemohon->nama} kini mempunyai role: {$data['role']}.")
                        ->success()
                        ->send();
                })
                ->visible(fn () => auth()->user()?->can('update', $this->record) ?? true), // laraskan ikut policy anda
        ];
    }
}
