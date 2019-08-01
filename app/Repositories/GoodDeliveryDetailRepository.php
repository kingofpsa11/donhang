<?php
namespace App\Repositories;

use App\GoodDeliveryDetail;
use Carbon\Carbon;

class GoodDeliveryDetailRepository
{
    protected $goodDeliveryDetailo;

    public function __construct(GoodDeliveryDetail $goodDeliveryDetailo)
    {
        $this->goodDeliveryDetailo = $goodDeliveryDetailo;
    }

    public function create($goodDeliveryId , $goodReceiveDetail, $bomDetail)
    {
        $store = $goodReceiveDetail->store->childrenStore ? $goodReceiveDetail->store->childrenStore : $goodReceiveDetail->store_id;

        GoodDeliveryDetail::create([
            'good_delivery_id' => $goodDeliveryId,
            'good_receive_detail_id' => $goodReceiveDetail->id,
            'product_id' => $bomDetail->product_id,
            'actual_quantity' => $goodReceiveDetail->quantity * $bomDetail->quantity,
            'store_id' => $store,
        ]);
    }
}