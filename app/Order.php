<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $guarded = ['id'];

    protected $with = ['items'];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function items()
    {
        return $this->belongsToMany('App\Item')->withPivot('quantity', 'unit_price')->withTimestamps();
    }
}
