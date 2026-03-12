<?php

namespace App\Filament\Resources\PromotionApplications\Pages;

use App\Filament\Resources\PromotionApplications\PromotionApplicationResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPromotionApplications extends ListRecords
{
    protected static string $resource = PromotionApplicationResource::class;

//    protected function getHeaderActions(): array
//    {
//        return [
//            CreateAction::make(),
//        ];
//    }
}
