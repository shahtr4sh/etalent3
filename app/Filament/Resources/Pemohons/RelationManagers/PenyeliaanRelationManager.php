<?php

namespace App\Filament\Resources\Pemohons\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Actions\CreateAction;        // <-- Dari Actions, bukan Tables\Actions
use Filament\Actions\EditAction;          // <-- Dari Actions
use Filament\Actions\ViewAction;          // <-- Dari Actions
use Filament\Actions\DeleteAction;        // <-- Dari Actions
use Filament\Actions\BulkActionGroup;     // <-- Dari Actions
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use App\Models\PenyeliaanStaf;

class PenyeliaanRelationManager extends RelationManager
{
    protected static string $relationship = 'penyeliaan';

    protected static ?string $title = 'Penyeliaan Tesis';

    protected static ?string $recordTitleAttribute = 'tajuk';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Forms\Components\TextInput::make('idtesis')
                    ->required()
                    ->numeric()
                    ->label('ID Tesis'),
                Forms\Components\TextInput::make('tajuk')
                    ->required()
                    ->maxLength(255)
                    ->label('Tajuk'),
                Forms\Components\TextInput::make('idstud')
                    ->maxLength(50)
                    ->label('ID Student'),
                Forms\Components\TextInput::make('penyelia_utama')
                    ->maxLength(50)
                    ->label('Penyelia Utama'),
                Forms\Components\TextInput::make('penyelia_bersama')
                    ->maxLength(255)
                    ->label('Penyelia Bersama'),
                Forms\Components\TextInput::make('kod_prog')
                    ->maxLength(50)
                    ->label('Kod Program'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('tajuk')
            ->columns([
                TextColumn::make('idtesis')
                    ->numeric()
                    ->sortable()
                    ->label('ID'),
                TextColumn::make('tajuk')
                    ->searchable()
                    ->wrap()
                    ->limit(50)
                    ->label('Tajuk'),
                TextColumn::make('idstud')
                    ->searchable()
                    ->label('ID Student'),
                TextColumn::make('penyelia_utama')
                    ->searchable()
                    ->label('Penyelia Utama')
                    ->badge()
                    ->color('success'),
                TextColumn::make('penyelia_bersama')
                    ->searchable()
                    ->label('Penyelia Bersama')
                    ->badge()
                    ->color('info'),
                TextColumn::make('program.namaprog_bm')
                    ->label('Program')
                    ->default('-'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('penyelia_utama')
                    ->label('Penyelia Utama'),
            ])
            ->headerActions([
                CreateAction::make(),                    // <-- OK
            ])
            ->defaultSort('idtesis', 'desc');
    }

    public function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        $staffId = $this->getOwnerRecord()->staff_id;

        return PenyeliaanStaf::query()
            ->where(function($q) use ($staffId) {
                $q->where('penyelia_utama', $staffId)
                    ->orWhere('penyelia_bersama', 'LIKE', "%{$staffId}%");
            })
            ->with('program');
    }
}
