<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PoleWeight extends Model
{
    protected $fillable = [
        'product_id',
        'area',
        'weight',
        'ty_le_nhan_cong',
        'expense_of_pole_id',
        'unit_price',
        'price',
    ];

    public $timestamps = true;

    public function poleWeightDetails()
    {
        return $this->hasMany('App\PoleWeightDetail');
    }

    public function product()
    {
        return $this->belongsTo('App\Product');
    }

    public function expenseOfPole()
    {
        return $this->belongsTo('App\ExpenseOfPole');
    }
}
