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

    protected static ?string $navigationLabel = 'Permohonan';

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
                    ->label('ID Staf')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('nama_staf')
                    ->label('Nama Staf')
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
                    ->label('Gred Dipohon')
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
                    ->label('No. Rujukan')
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
                    ->label('Aktif')
                    ->color(fn ($state) => $state ? 'success' : 'danger')
                    ->formatStateUsing(fn ($state) => $state ? 'Aktif' : 'Tidak Aktif'),

                TextColumn::make('created_at')
                    ->label('Tarikh Mohon')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'DRAF' => 'Draf',
                        'DIHANTAR' => 'Dihantar',
                        'MENUNGGU_SEMAKAN' => 'Menunggu Semakan',
                        'DALAM_SEMAKAN' => 'Dalam Semakan',
                        'UNTUK_KELULUSAN' => 'Untuk Kelulusan',
                        'LULUS' => 'Lulus',
                        'TIDAK_LULUS' => 'Tidak Lulus',
                    ]),

                SelectFilter::make('is_active')
                    ->label('Status Aktif')
                    ->options([
                        1 => 'Aktif',
                        0 => 'Tidak Aktif',
                    ]),

                Filter::make('created_at')
                    ->form([
                        DatePicker::make('created_from')->label('Dari Tarikh'),
                        DatePicker::make('created_until')->label('Hingga Tarikh'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['created_from'], fn ($q) => $q->whereDate('created_at', '>=', $data['created_from']))
                            ->when($data['created_until'], fn ($q) => $q->whereDate('created_at', '<=', $data['created_until']));
                    }),
            ])
//            ->actions([
//                Actions\Action\ViewAction::make(),
//                Actions\Action\EditAction::make(),
//
//                Action::make('indicator')
//                    ->label('Indicator Status')
//                    ->icon('heroicon-o-chart-bar')
//                    ->color('info')
//                    ->modalHeading('Semakan Kelayakan Permohonan')
//                    ->modalSubmitAction(false)
//                    ->modalCancelActionLabel('Tutup')
//                    ->modalWidth('7xl')
//                    ->modalContent(function ($record) {
//                        return view('filament.pages.indicator-status', [
//                            'application' => $record,
//                            'staff' => $record->pemohon,
//                            'kelayakan' => $record->pemohon ? $record->pemohon->checkKelayakanForGred($record->gred_jawatan) : null,
//                        ]);
//                    }),
//
//                Action::make('approve')
//                    ->label('Approve')
//                    ->icon('heroicon-o-check-circle')
//                    ->color('success')
//                    ->requiresConfirmation()
//                    ->modalHeading('Luluskan Permohonan')
//                    ->modalDescription('Adakah anda pasti untuk meluluskan permohonan ini?')
//                    ->modalSubmitActionLabel('Ya, Luluskan')
//                    ->modalCancelActionLabel('Batal')
//                    ->action(function ($record) {
//                        $record->update([
//                            'status' => 'UNTUK_KELULUSAN',
//                        ]);
//
//                        Notification::make()
//                            ->title('Permohonan diluluskan')
//                            ->body('Status permohonan telah dikemaskini kepada UNTUK_KELULUSAN')
//                            ->success()
//                            ->send();
//                    })
//                    ->visible(fn ($record) => $record->status === 'DIHANTAR' || $record->status === 'DALAM_SEMAKAN'),
//
//
//            ])
////            ->bulkActions([
////                Tables\Actions\BulkActionGroup::make([
////                    Tables\Actions\DeleteBulkAction::make(),
////                ]),
////            ])
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
