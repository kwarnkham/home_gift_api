<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    protected $guarded = ['id'];
    protected $hidden = [
        'created_at', 'updated_at',
    ];

    public function item()
    {
        return $this->belongsTo('App\Item');
    }
}
