<?php

namespace App\Filament\Resources\Pemohons\Tables;

use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Actions\ActionGroup;

class PemohonsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('staff_id')
                    ->label('Staff ID')
                    ->searchable()
                    ->url(fn ($record) => route('app.profil.show', ['staff_id' => $record->staff_id]))
                    ->openUrlInNewTab(),

                TextColumn::make('nama')
                    ->label('Nama')
                    ->searchable()
                    ->url(fn ($record) => route('app.profil.show', ['staff_id' => $record->staff_id]))
                    ->openUrlInNewTab()
                    ->color('primary'),

                TextColumn::make('emel_rasmi')
                    ->label('Emel Rasmi')
                    ->searchable(),

                TextColumn::make('created_at')
                    ->label('Created At')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->label('Updated At')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->actions([
                EditAction::make()
                    ->label('Edit')
                    ->icon('heroicon-m-pencil-square')
                    ->color('warning')
                    ->modalHeading('Edit Data Staf')
                    ->modalWidth('lg'),
            ])
            ->bulkActions([]);
    }
}
