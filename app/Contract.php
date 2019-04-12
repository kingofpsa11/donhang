<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
    protected $fillable = ['id', 'number', 'customer_id', 'total_value', 'imprest', 'status'];

    public $timestamps = true;

    protected $attributes = [
        'status' => 10
    ];

    public function customer()
    {
        return $this->belongsTo('App\Customer');
    }

    public function price()
    {
        return $this->belongsTo('App\Price');
    }

    public function contact_details()
    {
        return $this->hasMany('App\ContractDetail');
    }
}
