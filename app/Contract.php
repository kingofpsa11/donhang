<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

/**
 * App\Contract
 *
 * @property int $id
 * @property string $number
 * @property int $customer_id
 * @property int|null $total_value
 * @property int|null $imprest
 * @property int $status
 * @property string|null $date
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\ContractDetail[] $contractDetails
 * @property-read \App\Customer $customer
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\ManufacturerOrder[] $manufacturerOrder
 * @property-read \App\Price $price
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Contract newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Contract newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Contract query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Contract whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Contract whereCustomerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Contract whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Contract whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Contract whereImprest($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Contract whereNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Contract whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Contract whereTotalValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Contract whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Contract extends Model
{
    protected $fillable = ['id', 'number', 'customer_id', 'total_value', 'imprest', 'status', 'date'];

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

    public function contractDetails()
    {
        return $this->hasMany('App\ContractDetail');
    }

    public function manufacturerOrder()
    {
        return $this->hasMany('App\ManufacturerOrder');
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
