<?php
namespace App\Repositories;

use App\GoodReceiveDetail;
use Carbon\Carbon;

class GoodReceiveDetailRepository
{
    protected $goodReceiveDetail;

    public function __construct(GoodReceiveDetail $goodReceiveDetail)
    {
        $this->goodReceiveDetail = $goodReceiveDetail;
    }

    public function index()
    {
        return GoodReceiveDetail::with('goodReceive.supplier', 'product')
            ->orderByDesc('id')
            ->get();
    }

    public function create($attributes , $i, $goodReceiveId)
    {
        return GoodReceiveDetail::firstOrCreate([
            'good_receive_id' => $goodReceiveId,
            'product_id' => $attributes->product_id[$i],
            'bom_id' => $attributes->bom_id[$i],
            'store_id' => $attributes->store_id[$i],
            'quantity' => $attributes->quantity[$i]
        ]);

    }
}