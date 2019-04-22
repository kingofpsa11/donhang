<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class ContractDetail extends Model
{
    protected $fillable = ['id', 'contract_id', 'price_id', 'quantity', 'selling_price', 'supplier', 'manufacturer_order_number', 'quantity_issue', 'deadline', 'note', 'status'];

    public $timestamps = true;

    protected $attributes = [
        'status' => 10
    ];

    public function contract()
    {
        return $this->belongsTo('App\Contract');
    }

    public function price()
    {
        return $this->belongsTo('App\Price');
    }

    public function outputOrderDetails()
    {
        return $this->hasMany('App\OutputOrderDetail');
    }
    public function setDeadlineAttribute($value)
    {
        $this->attributes['deadline'] = Carbon::createFromFormat(config('app.date_format'), $value)->format('Y-m-d');
    }

    public function getDeadlineAttribute($value)
    {
        if (isset($value)) {
            return Carbon::createFromFormat('Y-m-d', $value)->format(config('app.date_format'));
        }

        return $value;
    }

//    public function setSellingPriceAttribute($value)
//    {
//        $this->attributes['selling_price'] = (int)str_replace('.', '', $value);
//    }
//
//    public function getSellingPriceAttribute($value)
//    {
//        return number_format($value, 0, ',', '.');
//    }
}
