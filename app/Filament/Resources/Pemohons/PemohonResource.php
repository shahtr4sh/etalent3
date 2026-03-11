<?php

namespace App\Filament\Resources\Pemohons;

use App\Filament\Resources\Pemohons\Pages;
use App\Filament\Resources\Pemohons\Tables\PemohonsTable;
use App\Models\Pemohon;
use Filament\Forms\Components\FileUpload;
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
                Section::make('Staff Information')
                    ->schema([
                        FileUpload::make('gambar_profil')
                            ->label('Profile Picture')
                            ->image()
                            ->disk('public')
                            ->directory('./profile-pictures')
                            ->visibility('public')
                            ->imagePreviewHeight('150')
                            ->downloadable()
                            ->openable()
                            ->deletable()
                            ->removeUploadedFileButtonPosition('right')
                            ->getUploadedFileNameForStorageUsing(function ($file) {
                                // Rename file with timestamp to avoid conflicts
                                return time() . '_' . str_replace(' ', '_', $file->getClientOriginalName());
                            })
                            ->deleteUploadedFileUsing(function ($file) {
                                // Custom delete logic
                                if ($file && \Storage::disk('public')->exists($file)) {
                                    \Storage::disk('public')->delete($file);
                                    return true;
                                }
                                return false;
                            })
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/gif'])
                            ->maxSize(2048) // 2MB max
                            ->columnSpanFull(),

                        TextInput::make('staff_id')
                            ->label('Staff ID')
                            ->required()
                            ->disabled(),

                        TextInput::make('nama')
                            ->label('Name')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('emel_rasmi')
                            ->label('Official Email')
                            ->email()
                            ->maxLength(255),

                        TextInput::make('no_telefon')
                            ->label('Contact Number')
                            ->maxLength(255),

                        Select::make('status')
                            ->label('Status')
                            ->options([
                                'A' => 'Aktif',
                                'SB' => 'Bercuti',
                                'C' => 'Dipinjamkan',
                                'P' => 'Sambung Belajar Tanpa Tajaan',
                                'S' => 'Sambung Belajar Dengan Tajaan Yuran',
                                'T' => 'Tamat Perkhidmatan',
                            ])
                            ->native(false),

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
                            ->afterStateHydrated(function (Select $component, ?Pemohon $record): void {
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
