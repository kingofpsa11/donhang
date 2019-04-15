<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Price extends Model
{
    protected $fillable = ['id', 'product_id', 'purchase_price', 'selling_price', 'status', 'effective_date'];

    public $timestamps = true;

    public function contract_details()
    {
        return $this->hasMany('App\ContractDetail');
    }

    public function product()
    {
        return $this->belongsTo('App\Product');
    }
}
