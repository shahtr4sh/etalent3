<?php

namespace App\Filament\Resources\PromotionApplications;

use App\Filament\Resources\PromotionApplications\Pages;
use App\Models\Pemohon;
use App\Models\PromotionApplication;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Form;
use Filament\Infolists\Infolist;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use App\Filament\Resources\PromotionApplications\Pages\ListPromotionApplications;
use App\Filament\Resources\PromotionApplications\Pages\CreatePromotionApplication;
use App\Filament\Resources\PromotionApplications\Pages\EditPromotionApplication;
use App\Filament\Resources\PromotionApplications\Pages\ViewPromotionApplication;

class PromotionApplicationResource extends Resource
{
    protected static ?string $model = PromotionApplication::class;

    protected static ?string $recordTitleAttribute = 'reference_no';

    protected static string|null|\BackedEnum $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationLabel = 'Application';

    protected static ?int $navigationSort = 1;

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
                TextColumn::make('staff_id')
                    ->label('Staff ID')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('nama_staf')
                    ->label('Staff Name')
                    ->getStateUsing(function ($record) {
                        $staff = Pemohon::where('staff_id', $record->staff_id)->first();
                        return $staff?->nama ?? '-';
                    })
                    ->searchable(query: function ($query, $search) {
                        return $query->whereHas('staff', function ($q) use ($search) {
                            $q->where('nama', 'like', "%{$search}%");
                        });
                    }),

                TextColumn::make('gred_jawatan')
                    ->label('Applied Grade')
                    ->formatStateUsing(function ($state, $record) {
                        // Cari dalam select_jawatan berdasarkan gred_jawatan
                        $selectJawatan = \App\Models\SelectJawatan::where('gredJawatan', $state)->first();

                        if ($selectJawatan && $selectJawatan->kod_kump) {
                            return $selectJawatan->kod_kump . ' ' . $state; // Contoh: DS + 52 = DS52
                        }

                        return $state;
                    })
                    ->searchable(),

                TextColumn::make('reference_no')
                    ->label('Reference No.')
                    ->searchable(),

                BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'gray' => 'DRAF',
                        'warning' => 'DIHANTAR',
                        'info' => ['MENUNGGU_SEMAKAN', 'DALAM_SEMAKAN'],
                        'primary' => 'UNTUK_KELULUSAN',
                        'success' => 'LULUS',
                        'danger' => 'TIDAK_LULUS',
                    ])
                    ->formatStateUsing(fn ($state) => str_replace('_', ' ', $state)),

                BadgeColumn::make('is_active')
                    ->label('Active')
                    ->color(fn ($state) => $state ? 'success' : 'danger')
                    ->formatStateUsing(fn ($state) => $state ? 'Active' : 'Inactive'),

                TextColumn::make('created_at')
                    ->label('Applied Date')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'DRAFT' => 'Draf',
                        'DIHANTAR' => 'Dihantar',
                        'MENUNGGU_SEMAKAN' => 'Menunggu Semakan',
                        'DALAM_SEMAKAN' => 'Dalam Semakan',
                        'UNTUK_KELULUSAN' => 'Untuk Kelulusan',
                        'LULUS' => 'Lulus',
                        'TIDAK_LULUS' => 'Tidak Lulus',
                    ]),

                SelectFilter::make('is_active')
                    ->label('Active Status')
                    ->options([
                        1 => 'Active',
                        0 => 'Inactive',
                    ]),

                Filter::make('created_at')
                    ->form([
                        DatePicker::make('created_from')->label('From'),
                        DatePicker::make('created_until')->label('Untilphp'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['created_from'], fn ($q) => $q->whereDate('created_at', '>=', $data['created_from']))
                            ->when($data['created_until'], fn ($q) => $q->whereDate('created_at', '<=', $data['created_until']));
                    }),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPromotionApplications::route('/'),
            'create' => Pages\CreatePromotionApplication::route('/create'),
            'view' => Pages\ViewPromotionApplication::route('/{record}'),
            'edit' => Pages\EditPromotionApplication::route('/{record}/edit'),
        ];
    }
}
