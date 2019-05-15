<?php

namespace App\Http\Controllers;

use App\GoodDelivery;
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

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\GoodDelivery  $goodDelivery
     * @return \Illuminate\Http\Response
     */
    public function show(GoodDelivery $goodDelivery)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\GoodDelivery  $goodDelivery
     * @return \Illuminate\Http\Response
     */
    public function edit(GoodDelivery $goodDelivery)
    {
        //
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
}
