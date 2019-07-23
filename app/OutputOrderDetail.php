<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\OutputOrderDetail
 *
 * @property int $id
 * @property int $contract_detail_id
 * @property int $quantity
 * @property int $output_order_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\ContractDetail $contractDetail
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\GoodDeliveryDetail[] $goodDeliveryDetails
 * @property-read \App\OutputOrder $outputOrder
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OutputOrderDetail newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OutputOrderDetail newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OutputOrderDetail query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OutputOrderDetail whereContractDetailId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OutputOrderDetail whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OutputOrderDetail whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OutputOrderDetail whereOutputOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OutputOrderDetail whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OutputOrderDetail whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class OutputOrderDetail extends Model
{
    protected $fillable = ['id', 'contract_detail_id', 'quantity', 'date', 'output_order_id'];

    public $timestamps = true;

    public function outputOrder()
    {
        return $this->belongsTo('App\OutputOrder');
    }

    public function contractDetail()
    {
        return $this->belongsTo('App\ContractDetail');
    }

    public function goodDeliveryDetails()
    {
        return $this->hasMany('App\GoodDeliveryDetail');
    }

    protected $attributes = [
        'status' => 10
    ];
}
