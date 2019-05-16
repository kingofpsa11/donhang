<?php

namespace App\Http\Controllers;

use App\GoodDelivery;
use App\GoodDeliveryDetail;
use App\OutputOrder;
use Illuminate\Http\Request;

class GoodDeliveryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(OutputOrder $outputOrder)
    {
        return view('good-deliveries.create', compact('outputOrder'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, OutputOrder $outputOrder)
    {
        $goodDelivery = new GoodDelivery();

        $goodDelivery->output_order_id = $outputOrder->id;
        $goodDelivery->number = $this->getNewNumber();

        if ( $goodDelivery->save() ) {
            $goodDeliveryDetails = [];
            foreach ($request->goodDeliveryDetails as $value) {
                $goodDeliveryDetail = new GoodDeliveryDetail();
                $goodDeliveryDetail->good_delivery_id = $value['good_delivery_id'];
                $goodDeliveryDetail->output_order_detail_id = $value['output_order_detail_id'];
                $goodDeliveryDetail->quantity = $value['quantity'];
                array_push($goodDeliveryDetails, $goodDeliveryDetail);
            }

            if($goodDelivery->goodDeliveryDetails()->saveMany($goodDeliveryDetails)) {
                return redirect()->route('good-delivery.show', [$goodDelivery]);
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\GoodDelivery  $goodDelivery
     * @return \Illuminate\Http\Response
     */
    public function show(OutputOrder $outputOrder)
    {
        return view('good-deliveries.show', compact('outputOrder'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\GoodDelivery  $goodDelivery
     * @return \Illuminate\Http\Response
     */
    public function edit(OutputOrder $outputOrder)
    {
        return view('good-deliveries.edit', compact('outputOrder'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\GoodDelivery  $goodDelivery
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, GoodDelivery $goodDelivery)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\GoodDelivery  $goodDelivery
     * @return \Illuminate\Http\Response
     */
    public function destroy(GoodDelivery $goodDelivery)
    {
        //
    }

    public function getNewNumber()
    {
        $newNumber = GoodDelivery::whereYear('date', date('Y'))
                ->max('number');
        return $newNumber + 1;

    }
}
