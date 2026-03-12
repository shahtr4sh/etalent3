<?php

namespace App\Filament\Resources\PromotionApplications\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class PromotionApplicationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('staff_id')
                    ->required(),
                TextInput::make('gred_jawatan'),
                TextInput::make('reference_no'),
                Select::make('status')
                    ->options([
            'DRAF' => 'Draf',
            'DIHANTAR' => 'Dihantar',
            'MENUNGGU_SEMAKAN' => 'Menunggu semakan',
            'DIPULANGKAN' => 'Dipulangkan',
            'DALAM_SEMAKAN' => 'Dalam semakan',
            'UNTUK_KELULUSAN' => 'Untuk kelulusan',
            'PERLU_MAKLUMAT' => 'Perlu maklumat',
            'TANGGUH' => 'Tangguh',
            'LULUS' => 'Lulus',
            'TIDAK_LULUS' => 'Tidak lulus',
            'DITUTUP' => 'Ditutup',
        ])
                    ->default('DRAF')
                    ->required(),
                Toggle::make('is_active')
                    ->required(),
                TextInput::make('metadata'),
                TextInput::make('reviewed_by_staff_id'),
            ]);
    }
}
