<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

/**
 * App\GoodDelivery
 *
 * @property int $id
 * @property int|null $output_order_id
 * @property int|null $good_receive_id
 * @property int $number
 * @property string $date
 * @property int $customer_id
 * @property string|null $customer_user
 * @property int $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Customer $customer
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\GoodDeliveryDetail[] $goodDeliveryDetails
 * @property-read \App\OutputOrder|null $outputOrder
 * @method static \Illuminate\Database\Eloquent\Builder|\App\GoodDelivery newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\GoodDelivery newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\GoodDelivery query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\GoodDelivery whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\GoodDelivery whereCustomerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\GoodDelivery whereCustomerUser($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\GoodDelivery whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\GoodDelivery whereGoodReceiveId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\GoodDelivery whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\GoodDelivery whereNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\GoodDelivery whereOutputOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\GoodDelivery whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\GoodDelivery whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class GoodDelivery extends Model
{
    protected $fillable = ['id', 'output_order_id', 'good_receive_id', 'number', 'date', 'customer_id', 'status'];

//    protected $dates = ['date'];

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

    public static function getNewNumber()
    {
        return (self::whereYear('date', date('Y'))->max('number') + 1) ?? 1;
    }

}
