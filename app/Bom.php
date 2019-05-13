<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bom extends Model
{
    protected $fillable = ['id', 'name', 'product_id', 'status', 'stage'];

    public $timestamps = true;

    public function product()
    {
        return $this->belongsTo('App\Product');
    }
}
