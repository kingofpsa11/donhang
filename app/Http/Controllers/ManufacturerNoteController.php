<?php

namespace App\Http\Controllers;

use App\ManufacturerNote;
use App\ManufacturerNoteDetail;
use App\ManufacturerOrder;
use Illuminate\Http\Request;

class ManufacturerNoteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $manufacturerNoteDetails = ManufacturerNoteDetail::all();
        return view('manufacturer-note.index', compact('manufacturerNoteDetails'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(ManufacturerOrder $manufacturerOrder)
    {
        $manufacturerOrder->load('manufacturerOrderDetails.contractDetail.price.product','contract.contractDetails');
        return view('manufacturer-note.create', compact('manufacturerOrder'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $manufacturerNote = new ManufacturerNote();
        $manufacturerNote->number = $request->number;
        $manufacturerNote->date = $request->date;

        if ($manufacturerNote->save()) {
            foreach ($request->manufacturerNoteDetails as $value) {
                $manufacturerNoteDetail = new ManufacturerNoteDetail();
                $manufacturerNoteDetail->manufacturer_note_id = $manufacturerNote->id;
                $manufacturerNoteDetail->contract_detail_id = $value['contract_detail_id'];
                $manufacturerNoteDetail->product_id = $value['product_id'];
                $manufacturerNoteDetail->quantity = $value['quantity'];
                $manufacturerNoteDetail->save();
            }
        }
        return view('manufacturer-note.show', compact('manufacturerNote'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\ManufacturerNote  $manufacturerNote
     * @return \Illuminate\Http\Response
     */
    public function show(ManufacturerNote $manufacturerNote)
    {
        $manufacturerNote->load('manufacturerNoteDetails.contractDetail.manufacturerOrderDetail');
        return view('manufacturer-note.show', compact('manufacturerNote'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ManufacturerNote  $manufacturerNote
     * @return \Illuminate\Http\Response
     */
    public function edit(ManufacturerNote $manufacturerNote)
    {
        return view('manufacturer-note.edit', compact('manufacturerNote'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ManufacturerNote  $manufacturerNote
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ManufacturerNote $manufacturerNote)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ManufacturerNote  $manufacturerNote
     * @return \Illuminate\Http\Response
     */
    public function destroy(ManufacturerNote $manufacturerNote)
    {
        //
    }
}
