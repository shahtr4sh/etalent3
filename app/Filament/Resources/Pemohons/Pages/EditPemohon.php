<?php

namespace App\Filament\Resources\Pemohons\Pages;

use App\Filament\Resources\Pemohons\PemohonResource;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditPemohon extends EditRecord
{
    protected static string $resource = PemohonResource::class;

    protected ?string $selectedRoleToSync = null;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $this->selectedRoleToSync = $data['role_name'] ?? 'none';

        unset($data['role_name']);

        return $data;
    }

    protected function afterSave(): void
    {
        $user = $this->record->user;

        if (! $user) {
            Notification::make()
                ->title('Rekod user tidak dijumpai')
                ->body('Role tidak dapat dikemaskini kerana user bagi staff ini tidak wujud.')
                ->danger()
                ->send();

            return;
        }

        $selectedRole = $this->selectedRoleToSync ?? 'none';

        if ($selectedRole === 'none') {
            $user->syncRoles([]);
        } else {
            $user->syncRoles([$selectedRole]);
        }

        $user->refresh();

        Notification::make()
            ->title('Role berjaya dikemaskini')
            ->body('Role semasa: ' . ($selectedRole === 'none' ? 'Tiada Role' : $selectedRole))
            ->success()
            ->send();
    }
}
