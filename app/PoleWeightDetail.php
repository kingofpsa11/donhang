<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PoleWeightDetail extends Model
{
    protected $fillable = [
        'pole_weight_id',
        'name',
        'shape',
        'quantity',
        'd_ngon',
        'd_goc',
        'day',
        'chieu_cao',
        'chieu_dai',
        'chieu_rong',
        'dien_tich',
        'khoi_luong',
        'status'
    ];

    public $timestamps = true;

    protected $attributes = [
        'status' => 10
    ];
}
