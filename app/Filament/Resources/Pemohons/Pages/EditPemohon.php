<?php

namespace App\Filament\Resources\Pemohons\Pages;

use App\Filament\Resources\Pemohons\PemohonResource;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditPemohon extends EditRecord
{
    protected static string $resource = PemohonResource::class;

    protected ?string $selectedRoleToSync = null;
    protected ?string $nameToSync = null;
    protected ?string $emailToSync = null;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $this->selectedRoleToSync = $data['role_name'] ?? 'none';
        $this->nameToSync = $data['nama'] ?? null;
        $this->emailToSync = $data['emel_rasmi'] ?? null;

        unset($data['role_name']);

        return $data;
    }

    protected function afterSave(): void
    {
        $user = $this->record->user;

        if (! $user) {
            Notification::make()
                ->title('Data pemohon berjaya dikemaskini')
                ->warning()
                ->send();

            return;
        }

        if ($this->nameToSync !== null) {
            $user->name = $this->nameToSync;
        }

        if ($this->emailToSync !== null) {
            $user->email = $this->emailToSync;
        }

        $user->save();

        $selectedRole = $this->selectedRoleToSync ?? 'none';

        if ($selectedRole === 'none') {
            $user->syncRoles([]);
        } else {
            $user->syncRoles([$selectedRole]);
        }

        Notification::make()
            ->title('Data pemohon dan role berjaya dikemaskini')
            ->success()
            ->send();
    }
}
