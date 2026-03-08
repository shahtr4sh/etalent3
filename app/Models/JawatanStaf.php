<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JawatanStaf extends Model
{
    protected $table = 'staf_jawatan';
    protected $primaryKey = 'id_rec_jwt';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $casts = [
        'terkini' => 'boolean',
        'aktif'   => 'boolean',
    ];
}
