<?php

namespace App\Filament\Resources\Pemohons\Tables;

use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
//use Filament\Tables;
//use Filament\Tables\Actions\Action;
//use App\Filament\Resources\Pemohons\PemohonResource;
use Spatie\Permission\Models\Role;
use App\Models\User;

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
                    ->color('primary'), // clickable look

                TextColumn::make('emel_rasmi')
                    ->searchable(),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ]);
    }
}
