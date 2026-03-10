<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StafTatatertib extends Model
{
    protected $table = 'staf_tatatertib';

    protected $fillable = [
        'id',
        'no_staf',
        'description',
        'tarikh_conduct'
    ];

    public $timestamps = false;

    /**
     * Get the staff for this disciplinary record
     */
    public function staff()
    {
        return $this->belongsTo(Pemohon::class, 'no_staf', 'staff_id');
    }

    /**
     * Get formatted date
     */
    public function getFormattedDateAttribute()
    {
        return $this->tarikh_conduct
            ? \Carbon\Carbon::parse($this->tarikh_conduct)->format('d/m/Y')
            : '-';
    }
}
