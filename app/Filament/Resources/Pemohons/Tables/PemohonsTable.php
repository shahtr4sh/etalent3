<?php

namespace App\Filament\Resources\Pemohons\Tables;

use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Illuminate\Support\Facades\Auth;

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
                    ->label('Name')
                    ->searchable()
                    ->url(fn ($record) => route('app.profil.show', ['staff_id' => $record->staff_id]))
                    ->openUrlInNewTab()
                    ->color('primary'),

                TextColumn::make('emel_rasmi')
                    ->label('Official Email')
                    ->searchable(),

                // Optional: Show if user exists
                TextColumn::make('user_exists')
                    ->label('Has User')
                    ->badge()
                    ->getStateUsing(fn ($record) => $record->user ? 'Yes' : 'No')
                    ->color(fn ($state) => $state === 'Yes' ? 'success' : 'danger')
                    ->toggleable(isToggledHiddenByDefault: true),

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
            ->recordActions([

                EditAction::make()
                    ->label('Edit')
                    ->icon('heroicon-m-pencil-square')
                    ->color('primary')
                    ->modalHeading('Edit Staff Data')
                    ->modalWidth('lg')
                    ->visible(fn() => Auth::user()?->hasRole('super_admin')),
            ])
            ->modifyQueryUsing(fn ($query) => $query
                ->where('status', '!=', 'T')
                ->whereHas('user') // Hanya papar staff yang wujud dalam table users
            )
            ->defaultSort('created_at', 'desc');
    }
}
