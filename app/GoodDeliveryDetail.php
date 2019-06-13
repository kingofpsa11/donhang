<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GoodDeliveryDetail extends Model
{
    protected $fillable = ['id', 'output_order_detail_id', 'good_delivery_id', 'quantiy', 'store_id'];

    public $timestamps = true;

    public function goodDelivery()
    {
        return $this->belongsTo('App\GoodDelivery');
    }

    public function outputOrderDetail()
    {
        return $this->belongsTo('App\OutputOrderDetail');
    }

    public function product()
    {
        return $this->belongsTo('App\Product');
    }

    public function store()
    {
        return $this->belongsTo('App\Store');
    }

    protected $attributes = [
        'status' => 10,
        'quantity' => 0,
        'actual_quantity' => 0,
    ];
}
