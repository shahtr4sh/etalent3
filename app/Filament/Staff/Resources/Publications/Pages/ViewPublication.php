<?php

namespace App\Filament\Staff\Resources\Publications\Pages;

use App\Filament\Staff\Resources\Publications\PublicationResource;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas\Schema;

class ViewPublication extends ViewRecord
{
    protected static string $resource = PublicationResource::class;

    public function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('title')
                    ->label('Title'),

                TextEntry::make('type')
                    ->label('Type')
                    ->formatStateUsing(fn ($state) => ucwords(str_replace('_', ' ', $state))),

                TextEntry::make('all_authors')
                    ->label('Authors')
                    ->wrap(),

                TextEntry::make('publish_date')
                    ->label('Publish Date')
                    ->date('d/m/Y'),

                TextEntry::make('publisher')
                    ->label('Publisher')
                    ->placeholder('-'),

                TextEntry::make('journal')
                    ->label('Journal')
                    ->placeholder('-'),

                TextEntry::make('conference')
                    ->label('Conference')
                    ->placeholder('-'),

                TextEntry::make('pages')
                    ->label('Pages')
                    ->placeholder('-'),

                TextEntry::make('doi')
                    ->label('DOI')
                    ->placeholder('-'),

                TextEntry::make('evidence')
                    ->label('Evidence')
                    ->placeholder('-'),
            ]);
    }
}
