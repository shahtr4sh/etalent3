<?php

namespace App\Filament\Staff\Resources\Publications\Pages;

use App\Filament\Staff\Resources\Publications\PublicationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class EditPublication extends EditRecord
{
    protected static string $resource = PublicationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    public function infolist(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Maklumat Penerbitan')
                    ->schema([
                        TextEntry::make('title')
                            ->label('Tajuk'),
                        TextEntry::make('type')
                            ->label('Jenis')
                            ->badge()
                            ->color(fn ($state) => match($state) {
                                'journal' => 'success',
                                'book' => 'info',
                                'conference' => 'warning',
                                default => 'secondary',
                            }),
                        TextEntry::make('publish_date')
                            ->label('Tarikh Penerbitan')
                            ->date(),
                        TextEntry::make('doi')
                            ->label('DOI')
                            ->copyable(),
                        TextEntry::make('evidence')
                            ->label('Evidence')
                            ->url(fn ($state) => $state)
                            ->openUrlInNewTab(),
                    ])
                    ->columns(2),

                Section::make('Authors')
                    ->schema([
                        RepeatableEntry::make('authors')
                            ->label('')
                            ->state(fn ($record) => $record->authors?->values()->toArray() ?? [])
                            ->schema([
                                TextEntry::make('name')
                                    ->label('Nama'),

                                TextEntry::make('is_staff')
                                    ->label('Status')
                                    ->formatStateUsing(fn ($state) => $state ? 'Staf' : 'Luar'),

                                TextEntry::make('nostaf')
                                    ->label('Staff ID')
                                    ->placeholder('-'),
                            ])
                            ->columns(3),
                    ]),

                Section::make('Indexes')
                    ->schema([
                        TextEntry::make('indexes')
                            ->label('')
                            ->formatStateUsing(fn ($record) => $record->indexes->pluck('name')->join(', ') ?: '-'),
                    ]),
            ]);
    }
}
