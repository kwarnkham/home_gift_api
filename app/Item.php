<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $guarded = ['id'];

    protected $hidden = [
        'location_id', 'merchant_id', 'created_at', 'updated_at'
    ];

    protected $with = ['categories', 'images', 'location', 'merchant'];

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

    public function orders(){
        return $this->belongsToMany('App\Order')->withPivot('name', 'quantity', 'price', 'description', 'notice', 'weight', 'location_id', 'merchant_id')->withTimestamps();
    }
}
