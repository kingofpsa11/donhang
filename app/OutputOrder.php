<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class OutputOrder extends Model
{
    protected $fillable = ['id', 'customer_id', 'number', 'date'];

    public $timestamps = true;

    public function outputOrderDetails()
    {
        return $this->hasMany('App\OutputOrderDetail');
    }

    public function customer()
    {
        return $this->belongsTo('App\Customer');
    }

    public function goodDelivery()
    {
        return $this->hasOne('App\GoodDelivery');
    }

    public function setDateAttribute($value)
    {
        $this->attributes['date'] = Carbon::createFromTimestamp($value, 'Asia/Bangkok')->format('Y-m-d');
    }

    public function getDateAttribute($value)
    {
        return Carbon::createFromFormat('Y-m-d', $value, 'Asia/Bangkok')->format(config('app.date_format'));
    }
}
