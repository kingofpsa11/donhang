<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GoodDelivery extends Model
{
    protected $fillable = ['id', 'output_order_id', 'number', 'date'];

    public $timestamps = true;

    public function outputOrder()
    {
        return $this->belongsTo('App\OutputOrder');
    }

    public function goodDeliveryDetails()
    {
        return $this->hasMany('App\GoodDeliveryDetail');
    }

    protected $attributes = [
        'status' => 10,
    ];
}
