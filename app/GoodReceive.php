<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class GoodReceive extends Model
{
    protected $fillable = ['id', 'number', 'supplier_user', 'supplier_id', 'status', 'date', 'note'];

    public $timestamps = true;

    public function supplier()
    {
        return $this->belongsTo('App\Supplier');
    }

    public function goodReceiveDetails()
    {
        return $this->hasMany('App\GoodReceiveDetail');
    }

    public function goodDelivery()
    {
        return $this->hasOne('App\GoodDelivery');
    }

    public function setDateAttribute($value)
    {
        $this->attributes['date'] = Carbon::createFromFormat(config('app.date_format'), $value, 'Asia/Bangkok')->format('Y-m-d');
    }

    public function getDateAttribute($value)
    {
        if (isset($value)) {
            return Carbon::createFromFormat('Y-m-d', $value, 'Asia/Bangkok')->format(config('app.date_format'));
        }

        return $value;
    }

    protected $attributes = [
        'status' => 10,
    ];

    public function getNewNumber()
    {
        $this->number = ($this->whereYear('date', date('Y'))->max('number') + 1) ?? 1;
    }
}
