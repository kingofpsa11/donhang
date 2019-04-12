<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ContractDetail extends Model
{
    protected $fillable = ['id', 'contract_id', 'price_id', 'quantity', 'selling_price', 'supplier', 'manufacturer_order_number', 'quantity_issue', 'deadline', 'note', 'status'];

    public $timestamps = true;

    public function contract()
    {
        return $this->belongsTo('App\Contract');
    }

    protected $attributes = [
        'status' => 10
    ];
}
