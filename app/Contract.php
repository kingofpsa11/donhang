<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Contract extends Model
{
    protected $fillable = ['id', 'number', 'customer_id', 'total_value', 'imprest', 'status'];

    public $timestamps = true;

    protected $attributes = [
        'status' => 10
    ];

    public function customer()
    {
        return $this->belongsTo('App\Customer');
    }

    public function price()
    {
        return $this->belongsTo('App\Price');
    }

    public function contract_details()
    {
        return $this->hasMany('App\ContractDetail');
    }

    public function setDateAttribute($value)
    {
        $this->attributes['date'] = Carbon::createFromFormat(config('app.date_format'), $value)->format('Y-m-d');
    }

    public function getDateAttribute($value)
    {
        return Carbon::createFromFormat('Y-m-d', $value)->format(config('app.date_format'));
    }

//    public function setTotalValueAttribute($value)
//    {
//        $this->attributes['total_value'] = (int)str_replace('.', '', $value);
//    }
//
//    public function getTotalValueAttribute($value)
//    {
//        return number_format($value, 0, ',', '.');
//    }
}
