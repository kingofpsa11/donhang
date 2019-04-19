<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = ['id', 'name', 'short_name', 'address'];

    public $timestamps = true;

    public function contracts()
    {
        return $this->hasMany('App\Contract');
    }

    public function outputOrders()
    {
        return $this->hasMany('App\OutputOrder');
    }
}
