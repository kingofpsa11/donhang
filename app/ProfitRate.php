<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProfitRate extends Model
{
    protected $fillable = ['customer_id', 'category_id', 'rate'];

    public $timestamps = true;

    public function customer()
    {
        return $this->belongsTo('App\Customer');
    }

    public function category()
    {
        return $this->belongsTo('App\Category');
    }

    public function getRateAttribute($value)
    {
        return str_replace('.',',', number_format($value, 3));
    }

    public function setRateAttribute($value)
    {
        $this->attributes['rate'] = str_replace(',', '.', $value);
    }
}
