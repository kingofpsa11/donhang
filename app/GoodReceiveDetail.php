<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\GoodReceiveDetail
 *
 * @property int $id
 * @property int $good_receive_id
 * @property int $product_id
 * @property int|null $bom_id
 * @property float $quantity
 * @property float|null $actual_quantity
 * @property int $store_id
 * @property int $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Bom|null $bom
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\GoodDeliveryDetail[] $goodDeliveryDetails
 * @property-read \App\GoodReceive $goodReceive
 * @property-read \App\Product $product
 * @property-read \App\Store $store
 * @method static \Illuminate\Database\Eloquent\Builder|\App\GoodReceiveDetail newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\GoodReceiveDetail newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\GoodReceiveDetail query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\GoodReceiveDetail whereActualQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\GoodReceiveDetail whereBomId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\GoodReceiveDetail whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\GoodReceiveDetail whereGoodReceiveId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\GoodReceiveDetail whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\GoodReceiveDetail whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\GoodReceiveDetail whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\GoodReceiveDetail whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\GoodReceiveDetail whereStoreId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\GoodReceiveDetail whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class GoodReceiveDetail extends Model
{
    protected $fillable = [
        'good_receive_id',
        'product_id',
        'bom_id',
        'quantity',
        'actual_quantity',
        'store_id',
        'status'
    ];

    public $timestamps = true;

    public function goodReceive()
    {
        return $this->belongsTo('App\GoodReceive');
    }

    public function goodDeliveryDetails()
    {
        return $this->hasMany('App\GoodDeliveryDetail');
    }

    public function product()
    {
        return $this->belongsTo('App\Product');
    }

    public function bom()
    {
        return $this->belongsTo('App\Bom');
    }

    public function store()
    {
        return $this->belongsTo('App\Store');
    }

    public function receive()
    {
        return $this->morphTo('App\StepNoteDetail');
    }

    protected $attributes = [
        'status' => 10
    ];
}
