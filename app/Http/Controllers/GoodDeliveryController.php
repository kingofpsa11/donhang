<?php

namespace App\Http\Controllers;

use App\GoodDelivery;
use App\GoodDeliveryDetail;
use Illuminate\Http\Request;

class GoodDeliveryController extends Controller
{
    protected $goodDelivery;

    public function __construct(GoodDelivery $goodDelivery)
    {
        $this->goodDelivery = $goodDelivery;
    }

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
        $newNumber = $this->goodDelivery->getNewNumber();
        return view('good-deliveries.create', compact('newNumber'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->goodDelivery->fill(
            $request->all()
        );

        if ($this->goodDelivery->save()) {
            foreach ($request->code as $key => $value) {
                GoodDeliveryDetail::create([
                    'good_delivery_id' => $this->goodDelivery->id,
                    'product_id' => $request->product_id[$key],
                    'actual_quantity' => $request->actual_quantity[$key],
                    'store_id' => $request->store_id[$key]
                ]);
            }
        }
        return redirect()->route('good-deliveries.show', $this->goodDelivery);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\GoodDelivery  $goodDelivery
     * @return \Illuminate\Http\Response
     */
    public function show(GoodDelivery $goodDelivery)
    {
        $goodDelivery->load('goodDeliveryDetails.product', 'goodDeliveryDetails.store');
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
        $goodDelivery->load('goodDeliveryDetails.product', 'goodDeliveryDetails.store');
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
        $goodDelivery->update([
            'number' => $request->number,
            'customer_id' => $request->customer_id,
            'date' => $request->date,
            'customer_user' => $request->customer_user,
        ]);

        $goodDelivery->goodDeliveryDetails()->update(['status' => 9]);

        for ($i = 0; $i < count($request->code); $i++) {
            $goodDelivery->goodDeliveryDetails()->updateOrCreate(
                [
                    'id' => $request->good_delivery_detail_id[$i]
                ],
                [
                    'product_id' => $request->product_id[$i],
                    'store_id' => $request->store_id[$i],
                    'status' => 10,
                    'actual_quantity' => $request->actual_quantity[$i],
                ]
            );
        }

        $goodDelivery->goodDeliveryDetails()->where('status',9)->delete();

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
        $goodDelivery->delete();
        flash('Đã xóa phiếu xuất ' . $goodDelivery->number)->warning();
        return redirect()->route('good-deliveries.index');
    }
}
