<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GelaranV extends Model
{
    protected $table = 'gelaran_v';

    protected $fillable = [
        'id_daftar',
        'kodgelaran',
        'gelaran',
    ];

    public $timestamps = false;

    /**
     * Get pemohon with this gelaran
     */
    public function pemohon()
    {
        return $this->hasMany(Pemohon::class, 'kod_gelaran', 'kodgelaran');
    }
}
