<?php

namespace App\Filament\Resources\Pemohons;

use App\Filament\Resources\Pemohons\Pages;
use App\Filament\Resources\Pemohons\Tables\PemohonsTable;
use App\Models\Pemohon;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class PemohonResource extends Resource
{
    protected static ?string $model = Pemohon::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-users';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Maklumat Pemohon')
                    ->schema([
                        TextInput::make('staff_id')
                            ->label('Staff ID')
                            ->required()
                            ->maxLength(255)
                            ->disabled(),

                        TextInput::make('nama')
                            ->label('Nama')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('emel_rasmi')
                            ->label('Emel Rasmi')
                            ->email()
                            ->required()
                            ->maxLength(255),

                        Select::make('role_name')
                            ->label('Role')
                            ->options([
                                'penyemak' => 'Penyemak',
                                'super_admin' => 'Super Admin',
                                'none' => 'Tiada Role',
                            ])
                            ->required()
                            ->native(false)
                            ->default('none')
                            ->afterStateHydrated(function (Select $component, ?\App\Models\Pemohon $record): void {
                                if (! $record) {
                                    $component->state('none');
                                    return;
                                }

                                $user = $record->user;

                                if (! $user) {
                                    $component->state('none');
                                    return;
                                }

                                if ($user->hasRole('super_admin')) {
                                    $component->state('super_admin');
                                    return;
                                }

                                if ($user->hasRole('penyemak')) {
                                    $component->state('penyemak');
                                    return;
                                }

                                $component->state('none');
                            }),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return PemohonsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPemohons::route('/'),
            'create' => Pages\CreatePemohon::route('/create'),
            'edit' => Pages\EditPemohon::route('/{record}/edit'),
        ];
    }
}
