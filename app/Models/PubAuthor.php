<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PubAuthor extends Model
{
    protected $table = 'pub_authors';
    protected $primaryKey = 'id';

    protected $fillable = [
        'pub_item_id',
        'name',
        'is_staff',
        'nostaf',
        'identity_id'
    ];

    public $timestamps = false;

    /**
     * Get the publication for this author
     */
    public function publication()
    {
        return $this->belongsTo(PenerbitanStaf::class, 'pub_item_id', 'id');
    }

    /**
     * Get staff details if available
     */
    public function staff()
    {
        return $this->belongsTo(Pemohon::class, 'nostaf', 'staff_id');
    }
}
