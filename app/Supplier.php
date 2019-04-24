<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $fillable = ['id', 'supplier_id', 'number'];

    public $timestamps = true;

    public function manufacturerOrders()
    {
        return $this->hasMany('App\ManufacturerOrder');
    }
}
