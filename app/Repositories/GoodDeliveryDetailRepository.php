<?php
namespace App\Repositories;

use App\GoodDeliveryDetail;
use Carbon\Carbon;

class GoodDeliveryDetailRepository
{
    protected $goodDeliveryDetail;

    public function __construct(GoodDeliveryDetail $goodDeliveryDetail)
    {
        $this->goodDeliveryDetail = $goodDeliveryDetail;
    }

    public function findByReceiveDetail($id)
    {
        return $this->goodDeliveryDetail->where('good_receive_detail_id', $id)->get();
    }

    public function create($goodDeliveryId , $attributes, $bomDetail)
    {
        $store = $attributes->store->childrenStore ? $attributes->store->childrenStore->id : $attributes->store_id;

        return GoodDeliveryDetail::create([
            'good_delivery_id' => $goodDeliveryId,
            'good_receive_detail_id' => $attributes->id,
            'product_id' => $bomDetail->product_id,
            'actual_quantity' => $attributes->quantity * $bomDetail->quantity,
            'store_id' => $store,
        ]);
    }

    public function update($attributes, $id)
    {
        return $this->goodDeliveryDetail->where('good_receive_detail_id', $id)->update($attributes);
    }

    public function updateOrCreate($id, $attributes, $bomDetail)
    {
        $store = $attributes->store->childrenStore ? $attributes->store->childrenStore->id : $attributes->store_id;

        return $this->goodDeliveryDetail->updateOrCreate(
            [
                'good_delivery_id' => $id,
                'good_receive_detail_id' => $attributes->id,
                'product_id' => $bomDetail->product_id,
            ],
            [
                'actual_quantity' => $attributes->quantity * $bomDetail->quantity,
                'store_id' => $store,
                'status' => 10
            ]
        );
    }

    public function deleteRemain($column, $value, $id)
    {
        return $this->goodDeliveryDetail->where('good_receive_detail_id', $id)->where($column, $value)->delete();
    }
}