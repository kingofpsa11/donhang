<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\GoodDeliveryDetail
 *
 * @property int $id
 * @property int|null $output_order_detail_id
 * @property int $good_delivery_id
 * @property int $good_receive_detail_id
 * @property int $product_id
 * @property float $quantity
 * @property float $actual_quantity
 * @property int|null $store_id
 * @property int $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\GoodDelivery $goodDelivery
 * @property-read \App\OutputOrderDetail|null $outputOrderDetail
 * @property-read \App\Product $product
 * @property-read \App\Store|null $store
 * @method static \Illuminate\Database\Eloquent\Builder|\App\GoodDeliveryDetail newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\GoodDeliveryDetail newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\GoodDeliveryDetail query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\GoodDeliveryDetail whereActualQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\GoodDeliveryDetail whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\GoodDeliveryDetail whereGoodDeliveryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\GoodDeliveryDetail whereGoodReceiveDetailId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\GoodDeliveryDetail whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\GoodDeliveryDetail whereOutputOrderDetailId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\GoodDeliveryDetail whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\GoodDeliveryDetail whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\GoodDeliveryDetail whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\GoodDeliveryDetail whereStoreId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\GoodDeliveryDetail whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class GoodDeliveryDetail extends Model
{
    protected $fillable = ['id', 'output_order_detail_id', 'good_delivery_id', 'good_receive_detail_id', 'product_id', 'quantity', 'actual_quantity', 'store_id', 'status'];

    public $timestamps = true;

    public function goodDelivery()
    {
        return $this->belongsTo('App\GoodDelivery');
    }

    public function outputOrderDetail()
    {
        return $this->belongsTo('App\OutputOrderDetail');
    }

    public function product()
    {
        return $this->belongsTo('App\Product');
    }

    public function store()
    {
        return $this->belongsTo('App\Store');
    }

    protected $attributes = [
        'status' => 10,
        'quantity' => 0,
        'actual_quantity' => 0,
    ];

}
