<?php

namespace App\Filament\Staff\Resources\Publications\Pages;

use App\Filament\Staff\Resources\Publications\PublicationResource;
use App\Models\PenerbitanStaf;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CreatePublication extends CreateRecord
{
    protected static string $resource = PublicationResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // ambil staff id user login dan masukkan ke pub_items.nostaf
        $data['nostaf'] = Auth::user()?->staff_id;

        return $data;
    }

    protected function handleRecordCreation(array $data): PenerbitanStaf
    {
        return DB::transaction(function () use ($data) {
            $authors = $data['authors'] ?? [];
            unset($data['authors']);

            // create pub_items sekali sahaja
            $record = PenerbitanStaf::create($data);

            $loginStaffId = Auth::user()?->staff_id;
            $loginStaffName = Auth::user()?->name ?? 'Unknown';

            // Pastikan author utama (staff yang create publication) masuk sekali
            $record->authors()->create([
                'name' => $loginStaffName,
                'nostaf' => $loginStaffId,
                'is_staff' => 1,
            ]);

            // 3. simpan author tambahan dari repeater
            foreach ($authors as $author) {
                $name = trim($author['name'] ?? '');
                $isStaff = (int) ($author['is_staff'] ?? 0);
                $nostaf = $author['nostaf'] ?? null;

                if ($name === '') {
                    continue;
                }

                // elak duplicate author utama kalau user masukkan lagi dalam repeater
                if ($isStaff === 1 && $nostaf === $loginStaffId) {
                    continue;
                }

                $record->authors()->create([
                    'name' => $name,
                    'is_staff' => $isStaff,
                    'nostaf' => $isStaff === 1 ? $nostaf : null,
                ]);
            }

            return $record;
        });
    }

    protected function afterCreate(): void
    {
        Notification::make()
            ->title('Publication berjaya disimpan')
            ->success()
            ->send();
    }
}
