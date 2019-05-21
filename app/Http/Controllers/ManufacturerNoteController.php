<?php

namespace App\Http\Controllers;

use App\ManufacturerNote;
use App\ManufacturerNoteDetail;
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
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('manufacturer-note.create');
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
        $manufacturerNote->number = $request->manufacturerNote['number'];
        $manufacturerNote->date = $request->manufacturerNote['date'];
        if ($manufacturerNote->save()) {
            $manufacturerNoteDetails = [];
            foreach ($request->manufacturerNoteDetails as $value) {
                $manufacturerNoteDetail = new ManufacturerNoteDetail();
                $manufacturerNoteDetail->manufacturer_note_id = $manufacturerNote->id;
                $manufacturerNoteDetail->contract_detail_id = $value['contract_detail_id'];
                $manufacturerNoteDetail->bom_id = $value['bom_id'];
                $manufacturerNoteDetail->quantity = $value['quantity'];
                array_push($manufacturerNoteDetails, $manufacturerNoteDetail);
            }

            if ($manufacturerNote->manufacturerNoteDetails()->saveMany($manufacturerNoteDetails)) {
                return view('manufacturer-note.show', compact('manufacturerNote'));
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\ManufacturerNote  $manufacturerNote
     * @return \Illuminate\Http\Response
     */
    public function show(ManufacturerNote $manufacturerNote)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ManufacturerNote  $manufacturerNote
     * @return \Illuminate\Http\Response
     */
    public function edit(ManufacturerNote $manufacturerNote)
    {
        //
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
