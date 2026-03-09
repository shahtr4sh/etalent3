<?php

namespace App\Filament\Resources\PromotionApplications\Pages;

use App\Filament\Resources\PromotionApplications\PromotionApplicationResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditPromotionApplication extends EditRecord
{
    protected static string $resource = PromotionApplicationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
