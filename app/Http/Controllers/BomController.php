<?php

namespace App\Http\Controllers;

use App\Bom;
use App\BomDetail;
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
        $bom = new Bom();
        $bom->product_id = $request->bom['product_id'];
        $bom->stage = $request->bom['stage'];
        $bom->name = $request->bom['name'];
        if ($bom->save()) {
            $bom_details = [];
            foreach ($request->bom_details as $value) {
                $bom_detail = new BomDetail();
                $bom_detail->bom_id = $bom->id;
                $bom_detail->product_id = $value['product_id'];
                $bom_detail->quantity = $value['quantity'];
                array_push($bom_details, $bom_detail);
            }

            if ($bom->bomDetails()->saveMany($bom_details)){
                return view('boms.show', compact('bom'));
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Bom  $bom
     * @return \Illuminate\Http\Response
     */
    public function show(Bom $bom)
    {
        return view('boms.show', compact('bom'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Bom  $bom
     * @return \Illuminate\Http\Response
     */
    public function edit(Bom $bom)
    {
        return view('boms.edit', compact('bom'));
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
