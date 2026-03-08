<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KelayakanJawatan extends Model
{
    protected $table = 'kelayakan_jawatan';

    protected $fillable = [
        'gredJawatan',
        'gred',
        'tahapAkademik',
        'countPenerbitan',
        'countPenyeliaan',
        'tahapKecermerangan',
        'jum_mark',
        'tahapPerkhidmatan',
        'tatatertib'
    ];

    public $timestamps = false;

    /**
     * Get kelayakan by gred
     */
    public static function getKelayakan($gred)
    {
        return self::where('gredJawatan', $gred)->first();
    }

    /**
     * Check if staff meets minimum requirements
     */
    public function checkKelayakan($staff)
    {
        $checks = [];

        // Check tahap akademik
        $staffAkademik = $staff->akademikTertinggi->kod_tahap ?? 0;
        if ($staffAkademik < $this->tahapAkademik) {
            $checks[] = 'Tahap akademik tidak mencukupi';
        }

        // Check jumlah penerbitan
        $staffPenerbitan = $staff->penerbitan->count() ?? 0;
        if ($staffPenerbitan < $this->countPenerbitan) {
            $checks[] = "Perlu {$this->countPenerbitan} penerbitan (ada {$staffPenerbitan})";
        }

        // Check jumlah penyeliaan - MANUAL QUERY
        $staffPenyeliaan = \App\Models\PenyeliaanStaf::where(function($q) use ($staff) {
            $q->where('penyelia_utama', $staff->staff_id)
                ->orWhere('penyelia_bersama', 'LIKE', "%{$staff->staff_id}%");
        })->count();

        if ($staffPenyeliaan < $this->countPenyeliaan) {
            $checks[] = "Perlu {$this->countPenyeliaan} penyeliaan (ada {$staffPenyeliaan})";
        }

        // Check markah prestasi
        $staffMarkah = $staff->markahTerkini->jum_mark ?? 0;
        if ($staffMarkah < $this->jum_mark) {
            $checks[] = "Markah prestasi perlu {$this->jum_mark}% (ada {$staffMarkah}%)";
        }

        return [
            'lulus' => empty($checks),
            'senarai_semak' => $checks
        ];
    }
}
