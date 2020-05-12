<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Township extends Model
{
    protected $guarded = ['id'];
    
    public function location()
    {
        return $this->belongsTo('App\Location');
    }
}
