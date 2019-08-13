<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

/**
 * App\OutputOrder
 *
 * @property int $id
 * @property int $customer_id
 * @property string|null $customer_user
 * @property int $number
 * @property string $date
 * @property int $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Customer $customer
 * @property-read \App\GoodDelivery $goodDelivery
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\OutputOrderDetail[] $outputOrderDetails
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OutputOrder newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OutputOrder newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OutputOrder query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OutputOrder whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OutputOrder whereCustomerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OutputOrder whereCustomerUser($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OutputOrder whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OutputOrder whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OutputOrder whereNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OutputOrder whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OutputOrder whereUpdatedAt($value)
 * @mixin \Eloquent
 */
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

    public function delivery()
    {
        return $this->morphOne('App\GoodDelivery', 'deliverable');
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
        'status' => 10
    ];
}
