<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StatusJawatan extends Model
{
    protected $table = 'status_jawatan';

    protected $primaryKey = 'kodStatus';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'kodStatus',
        'status',
        'masih_staf'
    ];

    /**
     * Get pemohon with this status
     */
    public function pemohon()
    {
        return $this->hasMany(Pemohon::class, 'status', 'kodStatus');
    }

    /**
     * Get status label for display
     */
    public function getLabelAttribute()
    {
        return $this->status . ' (' . $this->kodStatus . ')';
    }

    /**
     * Check if status is active (still staff)
     */
    public function getIsActiveAttribute()
    {
        return $this->masih_staf == 1;
    }
}
