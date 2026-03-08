<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pemohon extends Model
{
    protected $table = 'pemohon';

    protected $primaryKey = 'staff_id';
    public $incrementing = false;
    protected $keyType = 'string';

    public function gelaran()
    {
        return $this->belongsTo(GelaranV::class, 'kod_gelaran', 'kodgelaran');
    }

    public function getNamaDenganGelaranAttribute()
    {
        if ($this->gelaran) {
            return trim($this->gelaran->gelaran . ' ' . $this->nama);
        }
        return $this->nama;
    }

    public function getGelaranPrefixAttribute()
    {
        return $this->gelaran?->gelaran ?? '';
    }

    public function jawatanStaf()
    {
        return $this->hasMany(\App\Models\JawatanStaf::class, 'no_staf', 'staff_id');
    }

    public function jawatanStafTerkini()
    {
        return $this->hasOne(\App\Models\JawatanStaf::class, 'no_staf', 'staff_id')
            ->where('terkini', '1')
            ->orderByDesc('aktif'); // optional
    }

    public function jabatanStaf()
    {
        return $this->hasOne(\App\Models\JabatanStaf::class, 'no_staf', 'staff_id');
    }

    public function akademikStaf()
    {
        return $this->hasMany(\App\Models\AkademikStaf::class, 'no_staf', 'staff_id')
        ->orderBy('tahun_tamat', 'desc');
    }

    public function getPenyeliaanListAttribute()
    {
        return PenyeliaanStaf::where(function($q) {
            $q->where('penyelia_utama', $this->staff_id)
                ->orWhere('penyelia_bersama', 'LIKE', "%{$this->staff_id}%");
        })
            ->orderBy('idtesis', 'desc')
            ->get();
    }

    public function getPenyeliaanCountAttribute()
    {
        return PenyeliaanStaf::where(function($q) {
            $q->where('penyelia_utama', $this->staff_id)
                ->orWhere('penyelia_bersama', 'LIKE', "%{$this->staff_id}%");
        })
            ->count();
    }

    /**
     * Get publications list as attribute
     */
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

    /**
     * Get publications count as attribute
     */
    public function getPenerbitanCountAttribute()
    {
        $pubIds = PubAuthor::where('nostaf', $this->staff_id)
            ->pluck('pub_item_id')
            ->unique();

        return PenerbitanStaf::whereIn('id', $pubIds)->count();
    }

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

    /**
     * Relationship untuk Filament Relation Manager (penyeliaan)
     */
    public function penyeliaan()
    {
        $staffId = $this->staff_id;

        // Return a Relation instance (HasMany)
        return $this->hasMany(PenyeliaanStaf::class, 'penyelia_utama', 'staff_id')
            ->orWhere('penyelia_bersama', 'LIKE', "%{$staffId}%");
    }

    public function penyeliaanUtama()
    {
        return $this->hasMany(PenyeliaanStaf::class, 'penyelia_utama', 'staff_id');
    }

    public function penyeliaanBersama()
    {
        return $this->hasMany(PenyeliaanStaf::class, 'penyelia_bersama', 'staff_id');
    }

// Dalam relation manager, kita combine manually nanti

    public function markahTerkini()
    {
        return $this->hasOne(StafMarkah::class, 'no_staf', 'staff_id')
            ->orderBy('tahun_markah', 'desc')
            ->latest('tahun_markah');
    }

    public function akademikTertinggi()
    {
        return $this->hasOne(AkademikStaf::class, 'no_staf', 'staff_id')
            ->orderBy('kod_tahap', 'desc');
    }

    /**
     * Get all performance marks for staff
     */
    public function semuaMarkah()
    {
        return $this->hasMany(StafMarkah::class, 'no_staf', 'staff_id')
            ->orderBy('tahun_markah', 'desc');
    }

    /**
     * Permohonan kenaikan pangkat
     */
    public function promotionApplications()
    {
        return $this->hasMany(PromotionApplication::class, 'staff_id', 'staff_id')
            ->orderBy('created_at', 'desc');
    }



    protected $fillable = [
        'staff_id','kod_gelaran','nama','gred_semasa','jawatan_semasa',
        'ptj_fakulti','jabatan','emel_rasmi','no_telefon'
    ];
}
