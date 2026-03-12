<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Pemohon extends Model
{
    protected $table = 'pemohon';
    protected $primaryKey = 'staff_id';
    public $incrementing = false;
    protected $keyType = 'string';

    // Get Gelaran
    public function gelaran()
    {
        return $this->belongsTo(GelaranV::class, 'kod_gelaran', 'kodgelaran');
    }

    // Get Status Jawatan
    public function statusJawatan()
    {
        return $this->belongsTo(StatusJawatan::class, 'status', 'kodStatus');
    }

    // Get Status Label
    public function getStatusLabelAttribute()
    {
        return $this->statusJawatan?->status ?? $this->status;
    }

    // Get Staff Active Status
    public function getIsStaffActiveAttribute()
    {
        return $this->statusJawatan?->masih_staf == 1;
    }

    // Get Nama Dengan Gelaran
    public function getNamaDenganGelaranAttribute()
    {
        if ($this->gelaran) {
            return trim($this->gelaran->gelaran . ' ' . $this->nama);
        }
        return $this->nama;
    }

    // Get Gelaran Prefix
    public function getGelaranPrefixAttribute()
    {
        return $this->gelaran?->gelaran ?? '';
    }

    // Get Gelaran Suffix
    public function getGelaranSuffixAttribute()
    {
        return $this->gelaran?->gelaran_terakhir ?? '';
    }

    // Get Performance Evaluations
    public function performanceEvaluations()
    {
        return $this->hasMany(StafPerformance::class, 'no_staf', 'staff_id')
            ->orderBy('year', 'desc');
    }

    // Get Rekod Jawatan
    public function jawatanStaf()
    {
        return $this->hasMany(\App\Models\JawatanStaf::class, 'no_staf', 'staff_id');
    }

    // Get Jawatan Terkini
    public function jawatanStafTerkini()
    {
        return $this->hasOne(\App\Models\JawatanStaf::class, 'no_staf', 'staff_id')
            ->where('terkini', '1')
            ->orderByDesc('aktif'); // optional
    }

    // Get Jabatan Staf
    public function jabatanStaf()
    {
        return $this->hasOne(\App\Models\JabatanStaf::class, 'no_staf', 'staff_id');
    }

    // Get Rekod Tatatertib
    public function tatatertib()
    {
        return $this->hasMany(StafTatatertib::class, 'no_staf', 'staff_id')
            ->orderBy('tarikh_conduct', 'desc');
    }

    // Get Rekod Akademik
    public function akademikStaf()
    {
        return $this->hasMany(\App\Models\AkademikStaf::class, 'no_staf', 'staff_id')
        ->orderBy('tahun_tamat', 'desc');
    }

    // Get Rekod Penyeliaan
    public function getPenyeliaanListAttribute()
    {
        return PenyeliaanStaf::where(function($q) {
            $q->where('penyelia_utama', $this->staff_id)
                ->orWhere('penyelia_bersama', 'LIKE', "%{$this->staff_id}%");
        })
            ->orderBy('idtesis', 'desc')
            ->get();
    }

    // Get Count Penyeliaan
    public function getPenyeliaanCountAttribute()
    {
        return PenyeliaanStaf::where(function($q) {
            $q->where('penyelia_utama', $this->staff_id)
                ->orWhere('penyelia_bersama', 'LIKE', "%{$this->staff_id}%");
        })
            ->count();
    }

    // Get Rekod Penerbitan
    public function getPenerbitanListAttribute()
    {
        $pubIds = PubAuthor::where('nostaf', $this->staff_id)
            ->pluck('pub_item_id')
            ->unique();

        return PenerbitanStaf::whereIn('id', $pubIds)
            ->with(['authors', 'indexes'])
            ->orderBy('publish_date', 'desc')
            ->get();
    }

    // Get Count Penerbitan
    public function getPenerbitanCountAttribute()
    {
        $pubIds = PubAuthor::where('nostaf', $this->staff_id)
            ->pluck('pub_item_id')
            ->unique();

        return PenerbitanStaf::whereIn('id', $pubIds)->count();
    }

    // Penerbitan Relationship
    public function penerbitan()
    {
        return $this->hasManyThrough(
            PenerbitanStaf::class,
            PubAuthor::class,
            'nostaf',
            'id',
            'staff_id',
            'pub_item_id'
        )
            ->orderBy('publish_date', 'desc');
    }

    // Penyeliaan Relationship
    public function penyeliaan()
    {
        $staffId = $this->staff_id;

        return $this->hasMany(PenyeliaanStaf::class, 'penyelia_utama', 'staff_id')
            ->orWhere('penyelia_bersama', 'LIKE', "%{$staffId}%");
    }

    // Get Rekod Penyeliaan Utama Staf
    public function penyeliaanUtama()
    {
        return $this->hasMany(PenyeliaanStaf::class, 'penyelia_utama', 'staff_id');
    }

    // Get Rekod Penyeliaan Bersama Staf
    public function penyeliaanBersama()
    {
        return $this->hasMany(PenyeliaanStaf::class, 'penyelia_bersama', 'staff_id');
    }

    // Get Markah Terkini Staf (Untuk check kelayakan)
    public function markahTerkini()
    {
        return $this->hasOne(StafMarkah::class, 'no_staf', 'staff_id')
            ->orderBy('tahun_markah', 'desc')
            ->latest('tahun_markah');
    }

    // Get Akademik Tertinggi Staf
    public function akademikTertinggi()
    {
        return $this->hasOne(AkademikStaf::class, 'no_staf', 'staff_id')
            ->orderBy('kod_tahap', 'desc');
    }

    // Get Semua Markah Staf (Untuk Show Profile)
    public function semuaMarkah()
    {
        return $this->hasMany(StafMarkah::class, 'no_staf', 'staff_id')
            ->orderBy('tahun_markah', 'desc');
    }


    public function promotionApplications()
    {
        return $this->hasMany(PromotionApplication::class, 'staff_id', 'staff_id')
            ->orderBy('created_at', 'desc');
    }

    public function getRouteKeyName(): string
    {
        return 'staff_id';
    }

    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'staff_id', 'staff_id');
    }

    protected $fillable = [
        'staff_id','kod_gelaran','gambar_profil','nama',
        'emel_rasmi','no_telefon', 'status', 'is_active',
    ];
}
