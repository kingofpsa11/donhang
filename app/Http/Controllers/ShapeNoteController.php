<?php

namespace App\Http\Controllers;

use App\ShapeNote;
use App\ShapeNoteDetail;
use Illuminate\Http\Request;

class ShapeNoteController extends Controller
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
        $newNumber = ShapeNote::getNewNumber();
        return view('shape-note.create', compact('newNumber'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $shapeNote = ShapeNote::create($request->all());
        for ($i = 0; $i < count($request->code); $i++) {
            ShapeNoteDetail::create([
                'manufacturer_note_detail_id' => $request->manufacturer_note_detail_id[$i],
                'shape_note_id' => $shapeNote->id,
                'contract_detail_id' => $request->contract_detail_id[$i],
                'product_id' => $request->product_id[$i],
                'quantity' => $request->quantity[$i],
                'note' => $request->note[$i]
            ]);
        }

        return redirect()->route('shape-notes.show', $shapeNote);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\ShapeNote  $shapeNote
     * @return \Illuminate\Http\Response
     */
    public function show(ShapeNote $shapeNote)
    {
        $shapeNote->load(
            'shapeNoteDetails',
            'shapeNoteDetails.contractDetail.price.product',
            'shapeNoteDetails.manufacturerNoteDetail.product'
        );
        return view('shape-note.show', compact('shapeNote'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ShapeNote  $shapeNote
     * @return \Illuminate\Http\Response
     */
    public function edit(ShapeNote $shapeNote)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ShapeNote  $shapeNote
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ShapeNote $shapeNote)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ShapeNote  $shapeNote
     * @return \Illuminate\Http\Response
     */
    public function destroy(ShapeNote $shapeNote)
    {
        //
    }
}
