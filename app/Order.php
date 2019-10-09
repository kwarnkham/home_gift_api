<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Events\OrderCreated;

class Order extends Model
{
    protected $guarded = ['id'];

    public function promotion()
    {
        return $this->belongsTo('App\Promotion');
    }

    protected $with = ['items'];
    public function items()
    {
        return $this->belongsToMany('App\Item')->withPivot('name', 'quantity', 'price', 'description', 'notice', 'weight', 'location_id', 'merchant_id')->withTimestamps();
    }

    protected $dispatchesEvents = [
        'created' => OrderCreated::class,
    ];
}
