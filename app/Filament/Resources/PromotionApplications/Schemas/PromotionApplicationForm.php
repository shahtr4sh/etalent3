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
            'DRAF' => 'D r a f',
            'DIHANTAR' => 'D i h a n t a r',
            'MENUNGGU_SEMAKAN' => 'M e n u n g g u  s e m a k a n',
            'DIPULANGKAN' => 'D i p u l a n g k a n',
            'DALAM_SEMAKAN' => 'D a l a m  s e m a k a n',
            'UNTUK_KELULUSAN' => 'U n t u k  k e l u l u s a n',
            'PERLU_MAKLUMAT' => 'P e r l u  m a k l u m a t',
            'TANGGUH' => 'T a n g g u h',
            'LULUS' => 'L u l u s',
            'TIDAK_LULUS' => 'T i d a k  l u l u s',
            'DITUTUP' => 'D i t u t u p',
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
