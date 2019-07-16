<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class GoodDelivery extends Model
{
    protected $fillable = ['id', 'output_order_id', 'number', 'date'];

    public $timestamps = true;

    public function outputOrder()
    {
        return $this->belongsTo('App\OutputOrder');
    }

    public function goodDeliveryDetails()
    {
        return $this->hasMany('App\GoodDeliveryDetail');
    }

    public function customer()
    {
        return $this->belongsTo('App\Customer');
    }

    public function setDateAttribute($value)
    {
        $this->attributes['date'] = Carbon::createFromTimestamp($value, 'Asia/Bangkok')->format('Y-m-d');
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
        $this->number = self::whereYear('date', date('Y'))->max('number') + 1;
    }

    public function createNewDeliverBom(GoodReceive $goodReceive, GoodReceiveDetail $goodReceiveDetail) {
        $this->good_receive_id = $goodReceive->id;
        $this->getNewNumber();
        $this->date = strtotime(Carbon::createFromFormat('d/m/Y', $goodReceive->date, 'Asia/Bangkok')->format('Y-m-d'));
        $this->customer_id = $goodReceive->supplier_id;

        if ($this->save()) {
            $bom = Bom::getBomDetails($goodReceiveDetail->bom_id);

            foreach ($bom->bomDetails as $bomDetail) {
                $goodDeliveryBomDetail = new GoodDeliveryDetail();
                $goodDeliveryBomDetail->good_delivery_id = $this->id;
                $goodDeliveryBomDetail->good_receive_detail_id = $goodReceiveDetail->id;
                $goodDeliveryBomDetail->product_id = $bomDetail->product_id;
                $goodDeliveryBomDetail->actual_quantity = $goodReceiveDetail->quantity * $bomDetail->quantity;
                $goodDeliveryBomDetail->store_id = $goodReceiveDetail->store_id;
                $goodDeliveryBomDetail->save();
            }
        }
    }
}
