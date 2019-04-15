<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ContractDetail extends Model
{
    protected $fillable = ['id', 'contract_id', 'price_id', 'quantity', 'selling_price', 'supplier', 'manufacturer_order_number', 'quantity_issue', 'deadline', 'note', 'status'];

    public $timestamps = true;

    protected $attributes = [
        'status' => 10
    ];

    public function contract()
    {
        return $this->belongsTo('App\Contract');
    }

    public function price()
    {
        return $this->belongsTo('App\Price');
    }


}
