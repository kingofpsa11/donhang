<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ManufacturerOrder extends Model
{
    protected $fillable = ['id', 'number', 'status', 'supplier_id'];

    public $timestamps = true;

    public function contractDetails()
    {
        return $this->hasMany('App\ContractDetail');
    }
}
