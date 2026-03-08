<?php

namespace App\Filament\App\Resources;

use App\Filament\Resources\PromotionApplicationResource\Pages;
use App\Filament\Resources\PromotionApplicationResource\RelationManagers;
use App\Models\PromotionApplication;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PromotionApplicationResource extends Resource
{
    protected static ?string $model = PromotionApplication::class;

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationLabel = 'Permohonan Kenaikan Pangkat';

    protected static \UnitEnum|string|null $navigationGroup = 'Permohonan';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
            ])
            ->filters([
                //
            ])
            ->recordAction([])
            ->toolbarActions([

            ]);
    }

    public static function getRelations(): array
    {
        return [
            \App\Filament\Resources\PromotionApplicationResource\RelationManagers\ApplicationDocumentsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPromotionApplications::route('/'),
            'create' => Pages\CreatePromotionApplication::route('/create'),
            'edit' => Pages\EditPromotionApplication::route('/{record}/edit'),
        ];
    }
}
