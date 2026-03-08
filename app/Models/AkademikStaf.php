<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AkademikStaf extends Model
{
    protected $table = 'staf_akademik';

    protected $fillable = [
        'no_staf',
        'kod_tahap',
        'tahap_akademik',
        'kod_bidang',
        'tahun_tamat',
    ];
}
