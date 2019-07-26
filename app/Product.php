<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['id', 'name', 'name_bill', 'code', 'category_id', 'status', 'note', 'file'];

    public $timestamps = true;

    public function prices()
    {
        return $this->hasMany('App\Price');
    }

    public function category()
    {
        return $this->belongsTo('App\Category');
    }

    public function boms()
    {
        return $this->hasMany('App\Bom');
    }

    public function goodTransferDetails()
    {
        return $this->hasMany('App\GoodTransferDetail');
    }

    protected $attributes = [
        'status' => 10
    ];
}
