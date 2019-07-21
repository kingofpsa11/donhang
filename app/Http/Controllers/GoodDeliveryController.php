<?php

namespace App\Http\Controllers;

use App\GoodDelivery;
use App\GoodDeliveryDetail;
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
        $goodDeliveryDetails = GoodDeliveryDetail::all();
        return view('good-deliveries.index', compact('goodDeliveryDetails'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('good-deliveries.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $goodDelivery = new GoodDelivery();
        $goodDelivery->number = $request->goodDelivery['number'];
        $goodDelivery->customer_id = $request->goodDelivery['customer_id'];
        $goodDelivery->date = $request->goodDelivery['date'];
        $goodDelivery->customer_user = $request->goodDelivery['customer_user'];

        if ($goodDelivery->save()) {
            $goodDeliveryDetails = [];
            foreach ($request->goodDeliveryDetails as $value) {
                $goodDeliveryDetail = new GoodDeliveryDetail();
                $goodDeliveryDetail->good_delivery_id = $goodDelivery->id;
                $goodDeliveryDetail->product_id = $value['product_id'];
                $goodDeliveryDetail->quantity = $value['quantity'];
                $goodDeliveryDetail->store_id = $value['store_id'];
                array_push($goodDeliveryDetails, $goodDeliveryDetail);
            }

            if($goodDelivery->goodDeliveryDetails()->saveMany($goodDeliveryDetails)) {
                return redirect()->route('good-deliveries.show', [$goodDelivery]);
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\GoodDelivery  $goodDelivery
     * @return \Illuminate\Http\Response
     */
    public function show(GoodDelivery $goodDelivery)
    {
        return view('good-deliveries.show', compact('goodDelivery'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\GoodDelivery  $goodDelivery
     * @return \Illuminate\Http\Response
     */
    public function edit(GoodDelivery $goodDelivery)
    {
        return view('good-deliveries.edit', compact('goodDelivery'));
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
        if($request->goodDeliveryDetails[0]['actual_quantity'] != 0) {
            foreach ($request->goodDeliveryDetails as $value) {
                $goodDeliveryDetail = GoodDeliveryDetail::find($value['id']);
                $goodDeliveryDetail->actual_quantity = $value['actual_quantity'];
                $goodDeliveryDetail->save();
            }
        } else {
            $goodDelivery->number = $request->goodDelivery['number'];
            $goodDelivery->customer_id = $request->goodDelivery['customer_id'];
            $goodDelivery->date = $request->goodDelivery['date'];
            $goodDelivery->customer_user = $request->goodDelivery['customer_user'];

            $goodDelivery->goodDeliveryDetails()->update(['status' => 9]);

            if ($goodDelivery->save()) {
                foreach ($request->goodDeliveryDetails as $value) {
                    if (isset($value['id'])) {
                        $goodDeliveryDetail = GoodDeliveryDetail::find($value['id']);
                        $goodDeliveryDetail->product_id = $value['product_id'];
                        $goodDeliveryDetail->quantity = $value['quantity'];
                        $goodDeliveryDetail->store_id = $value['store_id'];
                        $goodDeliveryDetail->status = 10;
                        $goodDeliveryDetail->save();
                    } else {
                        $goodDeliveryDetail = new GoodDeliveryDetail();
                        $goodDeliveryDetail->good_delivery_id = $goodDelivery->id;
                        $goodDeliveryDetail->product_id = $value['product_id'];
                        $goodDeliveryDetail->quantity = $value['quantity'];
                        $goodDeliveryDetail->store_id = $value['store_id'];
                        $goodDeliveryDetail->status = 10;
                        $goodDeliveryDetail->save();
                    }
                }

                $goodDelivery->goodDeliveryDetails()->where('status',9)->delete();
            }
        }

        return view('good-deliveries.show', compact('goodDelivery'));
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
