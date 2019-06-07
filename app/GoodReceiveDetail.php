<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GoodReceiveDetail extends Model
{
    protected $fillable = [
        'good_receive_id',
        'product_id',
        'quantity',
        'store'
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
}
