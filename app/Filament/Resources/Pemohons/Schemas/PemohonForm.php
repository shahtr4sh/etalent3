<?php

namespace App\Filament\Resources\Pemohons\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;
use App\Models\StatusJawatan;

class PemohonForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('staff_id')
                    ->label('Staff ID')
                    ->required()
                    ->unique(ignoreRecord: true),

                TextInput::make('nama')
                    ->label('Nama Penuh')
                    ->required(),

                Select::make('kod_gelaran')
                    ->label('Gelaran')
                    ->relationship('gelaran', 'gelaran') // Ambil dari relationship
                    ->preload(),

                Select::make('status')
                    ->label('Status Perkhidmatan')
                    ->options(
                        StatusJawatan::all()->pluck('status', 'kodStatus')
                    )
                    ->searchable()
                    ->required()
                    ->default('A')
                    ->helperText('Pilih status perkhidmatan staf'),

                FileUpload::make('gambar_profil')
                    ->label('Gambar Profil')
                    ->image()
                    ->directory('profile-pictures') // Folder dalam storage
                    ->disk('public') // Guna public disk
                    ->visibility('public')
                    ->imageEditor()
                    ->maxSize(2048),

                TextInput::make('emel_rasmi')
                    ->label('Email Rasmi')
                    ->email()
                    ->required()
                    ->unique(ignoreRecord: true),

                TextInput::make('no_telefon')
                    ->label('No. Telefon')
                    ->tel()
                    ->required(),
            ]);
    }
}
