<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ManufacturerOrderDetail extends Model
{
    protected $fillable = ['manufacturer_order_id', 'contract_detail_id', 'status'];

    public $timestamps = true;

    public function manufacturerOrder()
    {
        return $this->belongsTo('App\ManufacturerOrder');
    }

    public function contractDetail()
    {
        return $this->belongsTo('App\ContractDetail');
    }

    protected $attributes = [
        'status' => 10
    ];
}
