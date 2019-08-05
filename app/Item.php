<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $guarded = ['id'];

    protected $hidden = [
        'location_id', 'merchant_id',
    ];

    public function location()
    {
        return $this->belongsTo('App\Location');
    }

    public function merchant()
    {
        return $this->belongsTo('App\Merchant');
    }

    public function images()
    {
        return $this->hasMany('App\Image');
    }

    public function categories()
    {
        return $this->belongsToMany('App\Category')->withTimestamps();;
    }
}
