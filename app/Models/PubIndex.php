<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PubIndex extends Model
{
    protected $table = 'pub_indexes';

    protected $fillable = [
        'id',
        'name'
    ];

    public $timestamps = false;

    /**
     * Get publications for this index
     */
    public function publications()
    {
        return $this->belongsToMany(
            PenerbitanStaf::class,
            'pub_item_indexes',
            'pub_index_id',
            'pub_item_id'
        );
    }
}
