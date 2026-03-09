<?php

namespace App\Filament\Resources\Pemohons\Pages;

use App\Filament\Resources\Pemohons\PemohonResource;
use Filament\Actions;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;

class ViewPemohon extends ViewRecord
{
    protected static string $resource = PemohonResource::class;

    public function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                // Profile Header dengan Gambar
                Section::make('Maklumat Peribadi')
                    ->compact()
                    ->components([
                        Grid::make(8)
                            ->schema([
                                // Column 1: Gambar
                                Grid::make(1)
                                    ->columnSpan(2)
                                    ->schema([
                                        ImageEntry::make('gambar_profil')
                                            ->label('')
                                            ->height(100) // Kecilkan sikit
                                            ->width(100)
                                            ->circular()
                                            ->defaultImageUrl(url('https://ui-avatars.com/api/?name=' . urlencode($this->record->nama) . '&size=100&background=8B5CF6&color=fff')),
                                    ])
                                    ->extraAttributes([
                                        'class' => 'flex items-start justify-center', // Centerkan gambar
                                    ]),

                                // Column 2: Info
                                Grid::make(3)
                                    ->columnSpan(10)
                                    ->schema([
                                        // Baris 1: ID Staf
                                        Grid::make(2)
                                            ->columnSpanFull()
                                            ->schema([
                                                TextEntry::make('staff_id')
                                                    ->label('ID Staf')
                                                    ->weight('bold')
                                                    ->size('lg'),
                                                TextEntry::make('staff_id')
                                                ->label('')
                                                    ->hidden(),
                                            ]),

                                        // Baris 2: Nama Penuh
                                        TextEntry::make('nama_dengan_gelaran')
                                            ->label('Nama Penuh')
                                            ->weight('bold')
                                            ->size('lg')
                                            ->columnSpanFull(),

                                        // Baris 3: Maklumat kontak
                                        Grid::make(2)
                                            ->columnSpanFull()
                                            ->schema([
                                                TextEntry::make('emel_rasmi')
                                                    ->label('Email Rasmi')
                                                    ->copyable()
                                                    ->copyMessage('Emel disalin!'),
                                                TextEntry::make('no_telefon')
                                                    ->label('No. Telefon')
                                                    ->copyable(),
                                                TextEntry::make('jabatanStaf.nama_jabatan')
                                                    ->label('Jabatan')
                                                    ->default('-'),
                                            ]),

                                        // Baris 4: Jawatan Hakiki dan Markah
                                        Grid::make(2)
                                            ->columnSpanFull()
                                            ->schema([
                                                TextEntry::make('jawatanStafTerkini')
                                                    ->label('Jawatan Hakiki')
                                                    ->formatStateUsing(function ($record) {
                                                        if (!$record->jawatanStafTerkini) return '-';
                                                        return $record->jawatanStafTerkini->nama_jawatan .
                                                            ' (' . $record->jawatanStafTerkini->gred_jawatan . ')';
                                                    }),
                                                TextEntry::make('markahTerkini.jum_mark')
                                                    ->label('Markah Prestasi')
                                                    ->formatStateUsing(fn ($state) => $state ? number_format($state, 2) . '%' : 'Tiada rekod')
                                                    ->color(fn ($state) => $state && $state >= 80 ? 'success' : 'danger')
                                                    ->badge(),
                                            ]),
                                    ]),
                            ]),
                    ]),

                // Tabs untuk rekod-rekod lain
                Tabs::make('Rekod')
                    ->tabs([
                        // Tab 1: Rekod Jawatan
                        Tab::make('Jawatan')
                            ->icon('heroicon-o-briefcase')
                            ->components([
                                RepeatableEntry::make('jawatanStaf')
                                    ->schema([
                                        TextEntry::make('nama_jawatan')
                                            ->label('Jawatan'),
                                        TextEntry::make('gred_jawatan')
                                            ->label('Gred'),
                                        TextEntry::make('terkini')
                                            ->label('Status')
                                            ->badge()
                                            ->formatStateUsing(fn ($state) => $state ? 'Aktif' : 'Tidak Aktif')
                                            ->color(fn ($state) => $state ? 'success' : 'danger'),
                                    ])
                                    ->columns(3),
                            ]),

                        // Tab 2: Rekod Akademik
                        Tab::make('Akademik')
                            ->icon('heroicon-o-academic-cap')
                            ->components([
                                RepeatableEntry::make('akademikStaf')
                                    ->schema([
                                        TextEntry::make('tahap_akademik')
                                            ->label('Tahap'),
                                        TextEntry::make('kod_bidang')
                                            ->label('Bidang'),
                                        TextEntry::make('tahun_tamat')
                                            ->label('Tahun Tamat'),
                                    ])
                                    ->columns(3),
                            ]),

                        // Tab 3: Rekod Penyeliaan
                        Tab::make('Penyeliaan')
                            ->icon('heroicon-o-user-group')
                            ->components([
                                RepeatableEntry::make('penyeliaanList')
                                    ->schema([
                                        TextEntry::make('tajuk')
                                            ->label('Tajuk Tesis')
                                            ->limit(999)
                                        ->columnSpan(2),

                                        TextEntry::make('program.namaprog_bm')
                                            ->label('Program')
                                            ->default('-'),
                                    ])
                                    ->columns(3),
                            ]),

                        // Tab 4: Rekod Penerbitan
                        Tab::make('Penerbitan')
                            ->icon('heroicon-o-document-text')
                            ->components([
                                RepeatableEntry::make('penerbitanList')
                                    ->schema([
                                        TextEntry::make('title')
                                            ->label('Tajuk')
                                            ->limit(99)
                                            ->columnSpan(2),
                                        TextEntry::make('type')
                                            ->label('Jenis')
                                            ->badge()
                                            ->color('info'),
                                        TextEntry::make('tahun')
                                            ->label('Tahun'),
                                        TextEntry::make('indexes')
                                        ->label('Indeks')
                                            ->formatStateUsing(fn ($record) =>
                                            $record->indexes->pluck('name')->implode(', ') ?: '-'
                                            ),
                                    ])
                                    ->columns(4),
                            ]),
                    ]),
            ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make()
                ->label('Edit Staf'),
            Actions\Action::make('back')
                ->label('Kembali ke Senarai')
                ->url(fn () => PemohonResource::getUrl('index'))
                ->color('gray'),
        ];
    }
}
