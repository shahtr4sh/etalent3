<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ImportJabatanStaf extends Command
{
    protected $signature = 'import:jabatan-staf';
    protected $description = 'Import jabatan staf dari kuin_psm ke etalent.jabatan_staf';

    public function handle(): int
    {
        // Ambil dari DB kuin_psm
        $rows = DB::connection('kuin_psm')
            ->table('stf_jabatan_v as s')
            ->leftJoin('jabatan_v as j', 'j.kodjabatan', '=', 's.kod_jab')
            ->select([
                DB::raw('TRIM(s.nostaf) as no_staf'),
                DB::raw('TRIM(s.kod_jab) as kod_jabatan'),
                'j.namajabatan as nama_jabatan',
                DB::raw('TRIM(s.kodunit) as kod_unit'),
            ])
            ->whereNotNull('s.nostaf')
            ->whereRaw("TRIM(s.nostaf) <> ''")
            ->whereNotNull('s.kod_jab')
            ->whereRaw("TRIM(s.kod_jab) <> ''")
            ->get();

        if ($rows->isEmpty()) {
            $this->warn('Tiada data untuk diimport.');
            return self::SUCCESS;
        }

        $this->info('Jumlah row daripada kuin_psm: '.$rows->count());

        // Insert/upsert ke DB etalent
        $payload = $rows->map(fn ($r) => [
            'no_staf' => $r->no_staf,
            'kod_jabatan' => $r->kod_jabatan,
            'nama_jabatan' => $r->nama_jabatan,
            'kod_unit' => $r->kod_unit,
            'created_at' => now(),
            'updated_at' => now(),
        ])->toArray();

        // Chunk untuk elak memory issue
        foreach (array_chunk($payload, 1000) as $chunk) {
            DB::table('jabatan_staf')->upsert(
                $chunk,
                ['no_staf', 'kod_jabatan', 'kod_unit'],
                ['nama_jabatan', 'updated_at']
            );
        }

        $this->info('Import siap.');
        return self::SUCCESS;
    }
}
