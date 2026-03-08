<?php

namespace App\Filament\Resources\Pemohons\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PemohonForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('kod_gelaran'),
                TextInput::make('nama')
                    ->required(),
                TextInput::make('emel_rasmi'),
                TextInput::make('no_telefon')
                    ->tel(),
                TextInput::make('status'),
            ]);
    }
}
