<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OutputOrder extends Model
{
    protected $fillable = ['id', 'customer_id', 'number', 'date'];

    public $timestamps = true;

    public function outputOrderDetails()
    {
        return $this->hasMany('App\OutputOrderDetail');
    }
}
