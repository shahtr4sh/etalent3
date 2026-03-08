<?php

namespace App\Filament\Resources\Pemohons\Tables;

use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PemohonsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('staff_id')
                    ->searchable(),
                TextColumn::make('nama')
                    ->searchable(),
                TextColumn::make('emel_rasmi')
                    ->searchable(),
                TextColumn::make('no_telefon')
                    ->searchable(),
                TextColumn::make('status')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                Action::make('CV')
                ->url(function ($record) {
                    return 'https://ukmsarjana.ukm.my/main/muatturun_cv/SzAwNzI4MQ==?nostaf' . $record->staff_id;
                })
                ->openUrlInNewTab(),

            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
