<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PenyeliaanStaf extends Model
{
    protected $table = 'staf_penyeliaan';

    protected $primaryKey = 'idtesis';

    public $incrementing = false;

    protected $keyType = 'int';

    protected $fillable = [
        'idtesis',
        'tajuk',
        'idstud',
        'penyelia_utama',
        'penyelia_bersama',
        'kod_prog',
    ];

    public $timestamps = false;

    /**
     * Get the program details
     */
    public function program()
    {
        return $this->belongsTo(TesisProgram::class, 'kod_prog', 'kod_prog');

    }

    /**
     * Check if given staff ID is the main supervisor
     */
    public function isPenyeliaUtama($staffId)
    {
        return $this->penyelia_utama == $staffId;
    }

    /**
     * Check if given staff ID is a co-supervisor
     */
    public function isPenyeliaBersama($staffId)
    {
        if (!$this->penyelia_bersama) {
            return false;
        }

        $coSupervisors = explode(',', $this->penyelia_bersama);
        return in_array($staffId, $coSupervisors);
    }

    /**
     * Get supervisor type for a specific staff
     */
    public function getJenisPenyelia($staffId)
    {
        if ($this->isPenyeliaUtama($staffId)) {
            return 'Penyelia Utama';
        }

        if ($this->isPenyeliaBersama($staffId)) {
            return 'Penyelia Bersama';
        }

        return '-';
    }
}
