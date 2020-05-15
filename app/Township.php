<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Township extends Model
{
    protected $guarded = ['id'];
    protected $hidden = [
        'created_at', 'updated_at',
    ];
    protected $with = ['location'];
    public function location()
    {
        return $this->belongsTo('App\Location');
    }

    public function address()
    {
        return $this->hasMany('App\Address');
    }
}
