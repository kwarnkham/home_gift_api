<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Events\OrderCreated;

class Order extends Model
{
    protected $guarded = ['id'];

    protected $with = ['promotion','items'];


    public function promotion()
    {
        return $this->belongsTo('App\Promotion');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }


    public function items()
    {
        return $this->belongsToMany('App\Item')->withPivot('name', 'quantity', 'price', 'description', 'notice', 'weight', 'location', 'merchant')->withTimestamps();
    }

    protected $dispatchesEvents = [
        'created' => OrderCreated::class,
    ];
}
