<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    protected $guarded = ['id'];
    protected $hidden = [
        'created_at', 'updated_at',
    ];
    protected $with = ['province'];

    public function items()
    {
        return $this->hasMany('App\Item');
    }

    public function province()
    {
        return $this->belongsTo('App\Province');
    }

    public function townships()
    {
        return $this->hasMany('App\Township');
    }
}
