<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StafMarkah extends Model
{
    protected $table = 'staf_markah';

    protected $fillable = [
        'id_eval',
        'jum_mark',
        'no_staf',
        'tahun_markah'
    ];

    public $timestamps = true;

    /**
     * Get mark percentage with % symbol
     */
    public function getMarkahPercentAttribute()
    {
        return number_format($this->jum_mark, 2) . '%';
    }

    /**
     * Get status class based on mark
     */
    public function getStatusClassAttribute()
    {
        return $this->jum_mark >= 80 ? 'text-green-600 bg-green-100' : 'text-red-600 bg-red-100';
    }

    /**
     * Get status text based on mark
     */
    public function getStatusTextAttribute()
    {
        return $this->jum_mark >= 80 ? 'Cemerlang' : 'Perlu Penambahbaikan';
    }

    /**
     * Get the staff for this mark
     */
    public function staff()
    {
        return $this->belongsTo(Pemohon::class, 'no_staf', 'staff_id');
    }
}
