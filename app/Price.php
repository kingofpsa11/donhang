<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Price extends Model
{
    protected $fillable = ['id', 'product_id', 'purchase_price', 'selling_price', 'status', 'effective_date'];

    public $timestamps = true;

    public function contracts()
    {
        return $this->hasMany('App\Contract');
    }

    public function product()
    {
        return $this->belongsTo('App\Product');
    }
}
