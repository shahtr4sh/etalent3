<?php

namespace App\Filament\Resources\PromotionApplications\Pages;

use App\Filament\Resources\PromotionApplications\PromotionApplicationResource;
use Filament\Actions\EditAction;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Pages\ViewRecord;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use App\Models\Pemohon;

class ViewPromotionApplication extends ViewRecord
{
    protected static string $resource = PromotionApplicationResource::class;
    protected $pemohonData = null;

    protected function getPemohon()
    {
        if ($this->pemohonData === null) {
            $this->pemohonData = Pemohon::with([
                'gelaran',
                'jabatanStaf',
                'jawatanStafTerkini',
                'markahTerkini'
            ])->where('staff_id', $this->record->staff_id)->first();
        }

        return $this->pemohonData;
    }

    public function infolist(Schema $schema): Schema
    {
        $pemohon = $this->getPemohon();

        if (!$pemohon) {
            return $schema->components([]);
        }

        return $schema
            ->components([
                // Maklumat Permohonan
                Section::make('Application Details')
                    ->compact()
                    ->components([
                        Grid::make(3)
                            ->schema([
                                TextEntry::make('reference_no')
                                    ->label('Reference Number'),
                                TextEntry::make('gred_jawatan')
                                    ->label('Applied Grade')
                                    ->formatStateUsing(function ($state) {
                                        $selectJawatan = \App\Models\SelectJawatan::where('gredJawatan', $state)->first();
                                        return $selectJawatan ? $selectJawatan->kod_kump . $state : $state;
                                    }),
                                TextEntry::make('status')
                                    ->label('Status')
                                    ->badge()
                                    ->color(fn ($state) => match ($state) {
                                        'DRAF' => 'gray',
                                        'DIHANTAR' => 'warning',
                                        'MENUNGGU_SEMAKAN' => 'info',
                                        'DALAM_SEMAKAN' => 'info',
                                        'UNTUK_KELULUSAN' => 'primary',
                                        'LULUS' => 'success',
                                        'TIDAK_LULUS' => 'danger',
                                        default => 'gray',
                                    }),
                            ]),
                    ]),

                // Profile Header dengan Gambar
                Section::make('Applicant Profile')
                    ->compact()
                    ->components([
                        Grid::make(1)
                            ->columnSpan(10)
                            ->schema([
                                // Row 1: Nama Penuh
                                TextEntry::make('nama_dengan_gelaran')
                                    ->label('Full Name')
                                    ->weight('bold')
                                    ->size('lg')
                                    ->getStateUsing(fn () => $pemohon->nama_dengan_gelaran),

                                // Row 2: ID Staf
                                TextEntry::make('staff_id')
                                    ->label('Staff ID')
                                    ->weight('bold')
                                    ->size('md')
                                    ->getStateUsing(fn () => $pemohon->staff_id),

                                // Row 3: Maklumat kontak
                                Grid::make(2)
                                    ->schema([
                                        TextEntry::make('emel_rasmi')
                                            ->label('Official Email')
                                            ->getStateUsing(fn () => $pemohon->emel_rasmi)
                                            ->copyable(),
                                        TextEntry::make('no_telefon')
                                            ->label('Contact Number')
                                            ->getStateUsing(fn () => $pemohon->no_telefon ?? '-')
                                            ->copyable(),
                                        TextEntry::make('jabatan')
                                            ->label('Department')
                                            ->getStateUsing(fn () => $pemohon->jabatanStaf->nama_jabatan ?? '-'),
                                        TextEntry::make('unit')
                                            ->label('Unit')
                                            ->getStateUsing(fn () => $pemohon->jabatanStaf->namaunit ?? '-'),
                                    ]),

                                // Baris 4: Jawatan Hakiki dan Markah
                                Grid::make(2)
                                    ->schema([
                                        TextEntry::make('jawatan_hakiki')
                                            ->label('Current Position')
                                            ->getStateUsing(function () use ($pemohon) {
                                                if (!$pemohon->jawatanStafTerkini) return '-';
                                                return $pemohon->jawatanStafTerkini->nama_jawatan .
                                                    ' (' . $pemohon->jawatanStafTerkini->gred_jawatan . ')';
                                            }),
                                        TextEntry::make('markah_prestasi')
                                            ->label('Performance Mark')
                                            ->getStateUsing(function () use ($pemohon) {
                                                if (!$pemohon->markahTerkini) return 'No record';
                                                return number_format($pemohon->markahTerkini->jum_mark, 2) . '%';
                                            })
                                            ->color(function () use ($pemohon) {
                                                if (!$pemohon->markahTerkini) return 'gray';
                                                return $pemohon->markahTerkini->jum_mark >= 80 ? 'success' : 'danger';
                                            })
                                            ->badge(),
                                    ]),
                            ]),
                        ]),
            ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('indicatorStatus')
                ->label('Indicator status')
                ->icon('heroicon-m-sparkles')
                ->color('info')
                ->modalHeading('Eligibility Status Indicators')
                ->modalWidth('4xl')
                ->modalSubmitAction(false) // info sahaja, tiada submit
                ->modalContent(function () {
                    $items = $this->buildIndicators(); // hasilkan data indikator
                    return view('filament.promotion-applications.indicators', [
                        'items' => $items,
                    ]);
                }),

            // Butang Approve (Admin)
            Actions\Action::make('approve')
                ->label('Approve')
                ->icon('heroicon-m-check-badge')
                ->color('success')
                ->requiresConfirmation()
                ->visible(function () {
                    // Tapis ikut role/policy/status semasa
                    $user = auth()->user();

                    // Fallback: hadkan kepada user yang ada role 'Admin'
                    return $user && method_exists($user, 'hasRole')
                        ? $user->hasRole('Admin') && $this->record->status !== 'UNTUK_KELULUSAN'
                        : $this->record->status !== 'UNTUK_KELULUSAN';
                })
                ->action(function () {
                    $this->record->update([
                        'status' => 'UNTUK_KELULUSAN',
                    ]);

                    Notification::make()
                        ->title('Application Sent for Approval')
                        ->success()
                        ->send();

                    // Refresh view
                    $this->refreshFormData(['status']);
                }),
        ];
    }

    protected function buildIndicators(): array
    {
        $application = $this->record; // permohonan semasa
        $pemohon = $this->getPemohon(); // data pemohon

        if (!$pemohon) {
            return [];
        }

        // Ambil data kelayakan berdasarkan gred jawatan
        $kelayakan = \App\Models\KelayakanJawatan::where('gredJawatan', $application->gred_jawatan)
            ->orWhere('gred', $application->gred_jawatan)
            ->first();

        if (!$kelayakan) {
            return [
                'error' => 'Tiada data kelayakan untuk gred ini.'
            ];
        }

        // Kira data pemohon
        $akademik = $pemohon->akademikTertinggi->kod_tahap ?? 0;
        $penerbitan = $pemohon->penerbitan()->count();
        $penyeliaan = $pemohon->penyeliaan()->count();
        $markah = $pemohon->markahTerkini->jum_mark ?? 0;

        // Result
        return [
            [
                'label' => 'Tahap Akademik',
                'required' => $kelayakan->tahapAkademik ?? '-',
                'current' => $akademik,
                'status' => $akademik >= ($kelayakan->tahapAkademik ?? 0),
                'unit' => 'Tahap',
            ],
            [
                'label' => 'Penerbitan',
                'required' => $kelayakan->countPenerbitan ?? 0,
                'current' => $penerbitan,
                'status' => $penerbitan >= ($kelayakan->countPenerbitan ?? 0),
                'unit' => 'item',
            ],
            [
                'label' => 'Penyeliaan',
                'required' => $kelayakan->countPenyeliaan ?? 0,
                'current' => $penyeliaan,
                'status' => $penyeliaan >= ($kelayakan->countPenyeliaan ?? 0),
                'unit' => 'item',
            ],
            [
                'label' => 'Markah Prestasi',
                'required' => $kelayakan->jum_mark ?? 0,
                'current' => $markah,
                'status' => $markah >= ($kelayakan->jum_mark ?? 0),
                'unit' => '%',
            ],
        ];
    }

}
