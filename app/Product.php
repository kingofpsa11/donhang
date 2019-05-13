<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['id', 'name', 'code', 'category_id'];

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

    protected $attributes = [
        'status' => 10
    ];
}
