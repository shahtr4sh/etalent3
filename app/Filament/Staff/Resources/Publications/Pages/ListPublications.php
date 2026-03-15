<?php

namespace App\Filament\Staff\Resources\Publications\Pages;

use App\Filament\Staff\Resources\Publications\PublicationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPublications extends ListRecords
{
    protected static string $resource = PublicationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Add New Publication')
                ->icon('heroicon-o-plus'),
        ];
    }

    public function getTitle(): string
    {
        return 'My Publications';
    }
}
