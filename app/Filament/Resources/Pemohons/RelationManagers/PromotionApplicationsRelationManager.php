<?php

namespace App\Filament\Resources\Pemohons\RelationManagers;

use Filament\Actions\CreateAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Forms;
use App\Models\PromotionApplication;
use App\Models\Pemohon;

class PromotionApplicationsRelationManager extends RelationManager
{
    protected static string $relationship = 'promotionApplications';

    protected static ?string $title = 'Permohonan Kenaikan Pangkat';

    protected static ?string $recordTitleAttribute = 'reference_no';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Forms\Components\TextInput::make('reference_no')
                    ->required()
                    ->maxLength(255)
                    ->label('No. Rujukan'),
                Forms\Components\TextInput::make('gred_jawatan')
                    ->maxLength(80)
                    ->label('Gred Dipohon'),
                Forms\Components\Select::make('status')
                    ->options([
                        'DRAF' => 'Draf',
                        'DIHANTAR' => 'Dihantar',
                        'MENUNGGU_SEMAKAN' => 'Menunggu Semakan',
                        'DIPULANGKAN' => 'Dipulangkan',
                        'DALAM_SEMAKAN' => 'Dalam Semakan',
                        'UNTUK_KELULUSAN' => 'Untuk Kelulusan',
                        'LULUS' => 'Lulus',
                        'TIDAK_LULUS' => 'Tidak Lulus',
                    ])
                    ->required(),
                Forms\Components\Toggle::make('is_active')
                    ->label('Aktif')
                    ->default(true),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('reference_no')
            ->columns([
                TextColumn::make('reference_no')
                    ->searchable()
                    ->sortable()
                    ->label('No. Rujukan'),
                TextColumn::make('gred_jawatan')
                    ->searchable()
                    ->label('Gred Dipohon'),
                TextColumn::make('created_at')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->label('Tarikh Cipta'),
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Permohonan Baru'),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
