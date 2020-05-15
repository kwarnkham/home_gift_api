<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $guarded = ['id'];

    protected $with =  ['location', 'township'];

    protected $hidden = [
         'created_at', 'updated_at'
    ];
    public function user()
    {
        return $this->hasOne('App\User');
    }

    public function location()
    {
        return $this->belongsTo('App\Location');
    }

    public function township()
    {
        return $this->belongsTo('App\Township');
    }
}
