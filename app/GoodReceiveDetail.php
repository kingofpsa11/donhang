<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GoodReceiveDetail extends Model
{
    protected $fillable = [
        'good_receive_id',
        'product_id',
        'bom_id',
        'quantity',
        'actual_quantity',
        'store_id',
        'status'
    ];

    public $timestamps = true;

    public function goodReceive()
    {
        return $this->belongsTo('App\GoodReceive');
    }

    public function product()
    {
        return $this->belongsTo('App\Product');
    }

    public function bom()
    {
        return $this->belongsTo('App\Bom');
    }

    public function store()
    {
        return $this->belongsTo('App\Store');
    }

    protected $attributes = [
        'status' => 10
    ];
}
