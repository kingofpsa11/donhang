<?php

namespace App\Http\Controllers;

use App\PriceQuotation;
use Illuminate\Http\Request;

class PriceQuotationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
//        return view('price-quotation.index');
        return view('price-quotation.vue');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $newNumber = PriceQuotation::whereYear('date', date('Y'))->max('number') + 1 ?? 1;
        return view('price-quotation.create', compact('newNumber'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\PriceQuotation  $priceQuotation
     * @return \Illuminate\Http\Response
     */
    public function show(PriceQuotation $priceQuotation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\PriceQuotation  $priceQuotation
     * @return \Illuminate\Http\Response
     */
    public function edit(PriceQuotation $priceQuotation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\PriceQuotation  $priceQuotation
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PriceQuotation $priceQuotation)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\PriceQuotation  $priceQuotation
     * @return \Illuminate\Http\Response
     */
    public function destroy(PriceQuotation $priceQuotation)
    {
        //
    }
}
