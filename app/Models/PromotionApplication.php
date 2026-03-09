<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PromotionApplication extends Model
{
    protected $table = 'promotion_applications';

    protected $fillable = [
        'staff_id',
        'gred_jawatan',
        'reference_no',
        'status',
        'is_active',
        'metadata'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'metadata' => 'array'
    ];

    public function pemohon()
    {
        return $this->belongsTo(Pemohon::class, 'staff_id', 'staff_id');
    }
//    public function staff()
//    {
//        return $this->belongsTo(Pemohon::class, 'staff_id', 'staff_id');
//    }
}
