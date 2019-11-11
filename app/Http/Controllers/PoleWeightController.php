<?php

namespace App\Http\Controllers;

use App\PoleWeight;
use Illuminate\Http\Request;

class PoleWeightController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('pole-weight.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pole-weight.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return $request->all();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\PoleWeight  $poleWeight
     * @return \Illuminate\Http\Response
     */
    public function show(PoleWeight $poleWeight)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\PoleWeight  $poleWeight
     * @return \Illuminate\Http\Response
     */
    public function edit(PoleWeight $poleWeight)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\PoleWeight  $poleWeight
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PoleWeight $poleWeight)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\PoleWeight  $poleWeight
     * @return \Illuminate\Http\Response
     */
    public function destroy(PoleWeight $poleWeight)
    {
        //
    }
}
