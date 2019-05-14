<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BomDetail extends Model
{
    protected $fillable = ['id', 'bom_id', 'product_id', 'quantity'];

    public $timestamps = true;

    public function bom()
    {
        return $this->belongsTo('App\Bom');
    }

    public function product()
    {
        return $this->belongsTo('App\Product');
    }
}
