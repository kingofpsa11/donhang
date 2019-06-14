<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OutputOrderDetail extends Model
{
    protected $fillable = ['id', 'contract_detail_id', 'quantity', 'date', 'output_order_id'];

    public $timestamps = true;

    public function outputOrder()
    {
        return $this->belongsTo('App\OutputOrder');
    }

    public function contractDetail()
    {
        return $this->belongsTo('App\ContractDetail');
    }

    public function goodDeliveryDetails()
    {
        return $this->hasMany('App\GoodDeliveryDetail');
    }

    protected $attributes = [
        'status' => 10
    ];
}
