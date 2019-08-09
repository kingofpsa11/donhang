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
        return $this->goodReceiveDetail->firstOrCreate([
            'good_receive_id' => $goodReceiveId,
            'product_id' => $attributes->product_id[$i],
            'bom_id' => $attributes->bom_id[$i],
            'store_id' => $attributes->store_id[$i],
            'quantity' => $attributes->quantity[$i]
        ]);
    }

    public function find($id)
    {
        return $this->goodReceiveDetail->find($id);
    }

    public function findByReceive($id)
    {
        return $this->goodReceiveDetail->where('good_receive_id', $id)->get();
    }

    public function update($attributes, $id)
    {
        return $this->goodReceiveDetail->where('good_receive_id', $id)->update($attributes);
    }

    public function updateOrCreate($attributes , $i, $goodReceiveId)
    {
        return GoodReceiveDetail::updateOrCreate(
            [
                'id' => $attributes->good_receive_detail_id[$i],
                'good_receive_id' => $goodReceiveId,
            ],
            [
                'product_id' => $attributes->product_id[$i],
                'unit' => $attributes->unit[$i],
                'bom_id' => $attributes->bom_id[$i],
                'store_id' => $attributes->store_id[$i],
                'quantity' => $attributes->quantity[$i],
                'status' => 10
            ]
        );
    }

    public function deleteRemain($column, $value, $id)
    {
        return $this->goodReceiveDetail->where('good_receive_id', $id)->where($column, $value)->delete();
    }
}