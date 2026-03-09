<?php

namespace App\Filament\Resources\PromotionApplications\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class PromotionApplicationInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('staff_id'),
                TextEntry::make('gred_jawatan')
                    ->placeholder('-'),
                TextEntry::make('reference_no')
                    ->placeholder('-'),
                TextEntry::make('status')
                    ->badge(),
                IconEntry::make('is_active')
                    ->boolean(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('reviewed_by_staff_id')
                    ->placeholder('-'),
            ]);
    }
}
