<?php

namespace App\Filament\Resources\Pemohons\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class PemohonInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('staff_id'),
                TextEntry::make('kod_gelaran')
                    ->placeholder('-'),
                TextEntry::make('nama'),
                TextEntry::make('emel_rasmi')
                    ->placeholder('-'),
                TextEntry::make('no_telefon')
                    ->placeholder('-'),
                TextEntry::make('status')
                    ->placeholder('-'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
