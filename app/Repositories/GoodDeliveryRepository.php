<?php
namespace App\Repositories;

use App\GoodDelivery;
use Carbon\Carbon;

class GoodDeliveryRepository
{
    protected $goodDelivery;

    public function __construct(GoodDelivery $goodDelivery)
    {
        $this->goodDelivery = $goodDelivery;
    }

    public function create($attributes)
    {
        return $this->goodDelivery->create($attributes);
    }

    public function firstOrCreate($model)
    {
        $date = Carbon::createFromFormat(config('app.date_format'), $model->date, 'Asia/Bangkok')->format('Y-m-d');

        $goodDelivery = $this->goodDelivery->where('good_receive_id', $model->id)
            ->where('date', $date)
            ->where('customer_id', $model->supplier_id)
            ->first();

        if (!$goodDelivery) {
            $goodDelivery = $this->goodDelivery->create([
                'good_receive_id' => $model->id,
                'date' => $model->date,
                'customer_id' => $model->supplier_id,
                'number' => GoodDelivery::getNewNumber()
            ]);
        }

        return $goodDelivery;
    }

    public function updateOrCreate($model)
    {
        $goodDelivery = $this->goodDelivery->where('good_receive_id', $model->id)
            ->first();

        if (!$goodDelivery) {
            $goodDelivery = $this->goodDelivery->create([
                'good_receive_id' => $model->id,
                'date' => $model->date,
                'customer_id' => $model->supplier_id,
                'number' => GoodDelivery::getNewNumber()
            ]);
        } else {
            $goodDelivery->update([
                'date' => $model->date,
                'customer_id' => $model->supplier_id,
            ]);
        }

        return $goodDelivery;
    }


}