<?php

namespace App\Filament\Resources\Pemohons;

use App\Filament\Resources\Pemohons\Pages\CreatePemohon;
use App\Filament\Resources\Pemohons\Pages\EditPemohon;
use App\Filament\Resources\Pemohons\Pages\ListPemohons;
use App\Filament\Resources\Pemohons\Pages\ViewPemohon;
use App\Filament\Resources\Pemohons\RelationManagers\PromotionApplicationsRelationManager;
use App\Filament\Resources\Pemohons\Schemas\PemohonForm;
use App\Filament\Resources\Pemohons\Schemas\PemohonInfolist;
use App\Filament\Resources\Pemohons\Tables\PemohonsTable;
use App\Models\Pemohon;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class   PemohonResource extends Resource
{
    protected static ?string $model = Pemohon::class;
    protected static ?string $navigationLabel = 'Staff';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'staff_id';

    public static function form(Schema $schema): Schema
    {
        return PemohonForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return PemohonInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PemohonsTable::configure($table);
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()
            ->where('status', '!=','T'); // Filter display status 'A'
    }

    public static function getRelations(): array
    {
        return [
            PromotionApplicationsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPemohons::route('/'),
            'create' => CreatePemohon::route('/create'),
            'view' => ViewPemohon::route('/{record}'),
            'edit' => EditPemohon::route('/{record}/edit'),
        ];
    }
}
