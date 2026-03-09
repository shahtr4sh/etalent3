<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SelectJawatan extends Model
{
    protected $table = 'select_jawatan';

    protected $fillable = [
        'id_daftar',
        'kodJawatan',
        'nama_jawatan',
        'nama_jawatan_bi',
        'kod_kump',
        'gredJawatan',
        'kategori'
    ];

    public $timestamps = false;

    /**
     * Get kelayakan for this jawatan
     */
    public function kelayakan()
    {
        return $this->hasOne(KelayakanJawatan::class, 'gred', 'gredJawatan');
    }

    /**
     * Check if jawatan has kelayakan data
     */
    public function hasKelayakan()
    {
        return !is_null($this->kelayakan);
    }

    /**
     * Get display name with gred
     */
    public function getDisplayNameAttribute()
    {
        return trim($this->nama_jawatan . ' (' . $this->kodJawatan . $this->gredJawatan . ')');
    }
}
