<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    protected $guarded = ['id'];
    protected $hidden = [
        'created_at', 'updated_at',
    ];

    public function items()
    {
        return $this->hasMany('App\Item');
    }
}
