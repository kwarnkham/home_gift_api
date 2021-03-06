<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    protected $guarded = ['id'];
    
    protected $hidden = [
        'created_at', 'updated_at',
    ];
    public function locations()
    {
        return $this->hasMany('App\Location');
    }
}
