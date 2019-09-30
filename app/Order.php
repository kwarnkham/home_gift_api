<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    public function promotion(){
        return $this->belongsTo('App\Promotion');
    }
}
