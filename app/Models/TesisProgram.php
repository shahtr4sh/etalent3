<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TesisProgram extends Model
{
    protected $table = 'tesis_program';

    protected $fillable = [
        'kod_prog',
        'kod_tahap',
        'namaprog_bm',
    ];

    public function penyeliaan()
    {
        return $this->hasMany(PenyeliaanStaf::class, 'kod_prog', 'kod_prog');
    }

    public function getDisplayNameAttribute()
    {
        return $this->namaprog_bm ?? $this->kod_prog;
    }

}
