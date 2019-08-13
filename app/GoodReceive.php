<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

/**
 * App\GoodReceive
 *
 * @property int $id
 * @property int $number
 * @property string|null $supplier_user
 * @property int $supplier_id
 * @property string $date
 * @property int $status
 * @property string|null $note
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\GoodDelivery $goodDelivery
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\GoodReceiveDetail[] $goodReceiveDetails
 * @property-read \App\Supplier $supplier
 * @method static \Illuminate\Database\Eloquent\Builder|\App\GoodReceive newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\GoodReceive newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\GoodReceive query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\GoodReceive whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\GoodReceive whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\GoodReceive whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\GoodReceive whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\GoodReceive whereNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\GoodReceive whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\GoodReceive whereSupplierId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\GoodReceive whereSupplierUser($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\GoodReceive whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class GoodReceive extends Model
{
    protected $fillable = ['id', 'number', 'supplier_user', 'supplier_id', 'status', 'date', 'note'];

    public $timestamps = true;

    protected $attributes = [
        'status' => 10,
    ];

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

    public function receive()
    {
        return $this->morphTo('App\StepNote');
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

    public static function getNewNumber()
    {
        return (self::whereYear('date', date('Y'))->max('number') + 1) ?? 1;
    }
}
