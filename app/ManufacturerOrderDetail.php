<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\ManufacturerOrderDetail
 *
 * @property int $id
 * @property int $manufacturer_order_id
 * @property int $contract_detail_id
 * @property int $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\ContractDetail $contractDetail
 * @property-read \App\ManufacturerOrder $manufacturerOrder
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ManufacturerOrderDetail newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ManufacturerOrderDetail newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ManufacturerOrderDetail query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ManufacturerOrderDetail whereContractDetailId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ManufacturerOrderDetail whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ManufacturerOrderDetail whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ManufacturerOrderDetail whereManufacturerOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ManufacturerOrderDetail whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ManufacturerOrderDetail whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ManufacturerOrderDetail extends Model
{
    protected $fillable = ['manufacturer_order_id', 'contract_detail_id', 'status'];

    public $timestamps = true;

    public function manufacturerOrder()
    {
        return $this->belongsTo('App\ManufacturerOrder');
    }

    public function contractDetail()
    {
        return $this->belongsTo('App\ContractDetail');
    }

    protected $attributes = [
        'status' => 10
    ];
}
