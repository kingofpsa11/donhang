<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $fillable = ['id', 'name', 'shortname', 'address'];

    public $timestamps = true;

    public function manufacturerOrders()
    {
        return $this->hasMany('App\ManufacturerOrder');
    }

    public function goodReceives()
    {
        return $this->hasMany('App\GoodReceive');
    }
}
