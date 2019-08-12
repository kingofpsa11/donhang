<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    protected $fillable = ['link', 'name', 'product_id'];

    public $timestamps = true;
}
