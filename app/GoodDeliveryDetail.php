<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GoodDeliveryDetail extends Model
{
    protected $fillable = ['id', 'output_order_detail_id', 'good_delivery_id', 'quantiy', 'store'];

    public $timestamps = true;

    public function goodDelivery()
    {
        return $this->belongsTo('App\GoodDelivery');
    }

    public function outputOrderDetail()
    {
        return $this->belongsTo('App\OutputOrderDetail');
    }
}
