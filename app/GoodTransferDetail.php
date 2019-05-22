<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GoodTransferDetail extends Model
{
    protected $fillable = ['id', 'good_transfer_id', 'product_id', 'bom_id', 'quantity'];

    public $timestamps = true;

    public function goodTransfer()
    {
        return $this->belongsTo('App\GoodTransfer');
    }

    public function product()
    {
        return $this->belongsTo('App\Product');
    }

    public function bom()
    {
        return $this->belongsTo('App\Bom');
    }
}
