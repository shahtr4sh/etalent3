<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StafPerformance extends Model
{
    protected $table = 'staf_performance';

    protected $fillable = [
        'id',
        'no_staf',
        'performance_mark',
        'year'
    ];

    public $timestamps = false;

    /**
     * Get the staff for this performance record
     */
    public function staff()
    {
        return $this->belongsTo(Pemohon::class, 'no_staf', 'staff_id');
    }

    /**
     * Get performance mark with % symbol
     */
    public function getMarkPercentAttribute()
    {
        return number_format($this->performance_mark, 1) . '%';
    }

    /**
     * Get status class based on mark
     */
    public function getStatusClassAttribute()
    {
        return $this->performance_mark >= 80 ? 'text-green-600' : 'text-yellow-600';
    }

    /**
     * Get status text based on mark
     */
    public function getStatusTextAttribute()
    {
        if ($this->performance_mark >= 90) return 'Cemerlang';
        if ($this->performance_mark >= 80) return 'Baik';
        if ($this->performance_mark >= 70) return 'Memuaskan';
        return 'Perlu Penambahbaikan';
    }
}
