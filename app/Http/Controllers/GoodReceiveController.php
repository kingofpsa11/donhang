<?php

namespace App\Http\Controllers;

use App\GoodReceiveDetail;
use App\GoodReceive;
use Illuminate\Http\Request;

class GoodReceiveController extends Controller
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
    public function create()
    {
        return view('good-receives.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $goodReceive = new GoodReceive();
        $goodReceive->number = $request->goodReceive['number'];
        $goodReceive->supplier_id = $request->goodReceive['supplier_id'];
        $goodReceive->date = $request->goodReceive['date'];
        $goodReceive->supplier_user = $request->goodReceive['supplier_user'];

        if ($goodReceive->save()) {
            $goodReceiveDetails = [];
            foreach ($request->goodReceiveDetails as $value) {
                $goodReceiveDetail = new GoodReceiveDetail();
                $goodReceiveDetail->good_receive_id = $goodReceive->id;
                $goodReceiveDetail->product_id = $value['product_id'];
                $goodReceiveDetail->quantity = $value['quantity'];
                $goodReceiveDetail->store_id = $value['store_id'];
                array_push($goodReceiveDetails, $goodReceiveDetail);
            }

            if($goodReceive->goodReceiveDetails()->saveMany($goodReceiveDetails)) {
                return redirect()->route('good-receive.show', [$goodReceive]);
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\GoodReceive  $goodReceive
     * @return \Illuminate\Http\Response
     */
    public function show(GoodReceive $goodReceive)
    {
        return view('good-receives.show', compact('goodReceive'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\GoodReceive  $goodReceive
     * @return \Illuminate\Http\Response
     */
    public function edit(GoodReceive $goodReceive)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\GoodReceive  $goodReceive
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, GoodReceive $goodReceive)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\GoodReceive  $goodReceive
     * @return \Illuminate\Http\Response
     */
    public function destroy(GoodReceive $goodReceive)
    {
        //
    }
}
