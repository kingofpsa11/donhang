<?php

namespace App\Http\Controllers;

use App\Bom;
use Illuminate\Http\Request;

class BomController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $boms = Bom::all();
        return view('boms.index',compact('boms'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('boms.create');
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
     * @param  \App\Bom  $bom
     * @return \Illuminate\Http\Response
     */
    public function show(Bom $bom)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Bom  $bom
     * @return \Illuminate\Http\Response
     */
    public function edit(Bom $bom)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Bom  $bom
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Bom $bom)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Bom  $bom
     * @return \Illuminate\Http\Response
     */
    public function destroy(Bom $bom)
    {
        //
    }
}
