<?php

namespace App\Filament\Resources\Pemohons\Pages;

use App\Filament\Resources\Pemohons\PemohonResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;

class CreatePemohon extends CreateRecord
{
    protected static string $resource = PemohonResource::class;

    protected function afterCreate(): void
    {
        // Open new tab with JavaScript
        $this->dispatch('open-profile', staffId: $this->record->staff_id);

        $selectedRole = $this->form->getState()['role_name'] ?? 'none';

        $user = $this->record->user;

        if (! $user) {
            Notification::make()
                ->title('Rekod user tidak dijumpai berdasarkan staff_id')
                ->body('Pemohon berjaya dicipta tetapi role tidak dapat ditetapkan.')
                ->warning()
                ->send();
            return;
        }
        if ($selectedRole === 'none') {
            $user->syncRoles([]);
        } else {
            $user->syncRoles([$selectedRole]);
        }
    }

    protected function getRedirectUrl(): string
    {
        return PemohonResource::getUrl('index');
    }

    public function mount(): void
    {
        parent::mount();

        $this->js = <<<'JS'
            window.addEventListener('open-profile', event => {
                window.open('/app/profil/' + event.detail.staffId, '_blank');
            });
        JS;
    }
}
