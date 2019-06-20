<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ManufacturerOrder extends Model
{
    protected $fillable = ['id', 'number', 'status', 'supplier_id'];

    public $timestamps = true;

    public function contract()
    {
        return $this->belongsTo('App\Contract');
    }

    public function supplier()
    {
        return $this->belongsTo('App\Supplier');
    }

    public function manufacturerOrderDetails()
    {
        return $this->hasMany('App\ManufacturerOrderDetail');
    }

    protected $attributes = [
        'status' => 10,
    ];

}
