<?php

namespace App\Http\Controllers;

use App\BomDetail;
use App\ManufacturerNoteDetail;
use App\ShapeNote;
use App\ShapeNoteDetail;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

class ShapeNoteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $shapeNoteDetails = ShapeNoteDetail::with(
            'shapeNote',
            'contractDetail.price.product',
            'manufacturerNoteDetail.product'
        )
        ->get();

        return view('shape-note.index', compact('shapeNoteDetails'));
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

        foreach ($request->details as $detail) {
            $shapeNote->shapeNoteDetails()->create($detail);

            $manufacturerNoteDetail = ManufacturerNoteDetail::find($detail['manufacturer_note_detail_id']);

            $quantity = $manufacturerNoteDetail->quantity;

            $bomQuantity = $detail['bom_detail_quantity'];

            if (ceil($quantity * $bomQuantity) == $detail['quantity']) {
                $manufacturerNoteDetail->contractDetail()->update(['status' => 8]);
            }
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
        $shapeNote->load(
            'shapeNoteDetails',
            'shapeNoteDetails.contractDetail.price.product',
            'shapeNoteDetails.manufacturerNoteDetail.product'
        );

        return view('shape-note.edit', compact('shapeNote'));
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
        $shapeNote->update($request->all());
        $shapeNote->shapeNoteDetails()->update(['status' => 9]);

        foreach ($request->details as $detail) {
            $id = $detail['id'];
            unset($detail['id']);
            $detail['status'] = 10;
            $shapeNote->shapeNoteDetails()->updateOrCreate(['id' => $id], $detail);
        }

        $shapeNote->shapeNoteDetails()->where(['status' => 9])->delete();

        return redirect()->route('shape-notes.show', $shapeNote);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ShapeNote  $shapeNote
     * @return \Illuminate\Http\Response
     */
    public function destroy(ShapeNote $shapeNote)
    {
        $shapeNote->delete();
        flash('Đã xoá phiếu cắt tấm số' . $shapeNote->number, 'warning');

        return redirect()->route('shape-notes.index');
    }
}
