<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['id', 'name', 'name_bill', 'code', 'unit', 'category_id', 'status', 'note', 'file'];

    public $timestamps = true;

    protected $attributes = [
        'status' => 10
    ];

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

    public function images()
    {
        return $this->hasMany('App\Image');
    }

    public static function existCode($code)
    {
        return self::where('code', $code)->count();
    }
}
