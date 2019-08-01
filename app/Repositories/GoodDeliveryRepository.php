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

        $goodDelivery = GoodDelivery::where('good_receive_id', $model->id)
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


}