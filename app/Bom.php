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

    public function bomDetails()
    {
        return $this->hasMany('App\BomDetail');
    }

    public function manufacturerNoteDetails()
    {
        return $this->hasMany('App\ManufacturerNoteDetail');
    }

    protected $attributes = [
        'status' => 10
    ];
}
