<?php

namespace App\Filament\Resources\Pemohons\Pages;

use App\Filament\Resources\Pemohons\PemohonResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditPemohon extends EditRecord
{
    protected static string $resource = PemohonResource::class;

    protected ?string $selectedRoleToSync = null;
    protected ?string $nameToSync = null;
    protected ?string $emailToSync = null;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->label('Delete User Account')
                ->icon('heroicon-o-trash')
                ->color('danger')
                ->requiresConfirmation()
                ->modalHeading('Delete User Account')
                ->modalDescription('Are you sure you want to delete this user account? This action cannot be undone.')
                ->modalSubmitActionLabel('Yes, delete account')
                ->action(function () {
                    $this->record->delete();
                }),
        ];
    }

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
                ->success()
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

    protected function getRedirectUrl(): string
    {
        return PemohonResource::getUrl('');
    }

}
