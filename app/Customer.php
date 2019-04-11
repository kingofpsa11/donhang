<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = ['id', 'name', 'short_name', 'address'];

    public $timestamps = true;
}
