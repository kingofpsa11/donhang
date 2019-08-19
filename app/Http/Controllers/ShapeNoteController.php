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

        for ($i = 0; $i < count($request->code); $i++) {
            ShapeNoteDetail::create([
                'manufacturer_note_detail_id' => $request->manufacturer_note_detail_id[$i],
                'shape_note_id' => $shapeNote->id,
                'contract_detail_id' => $request->contract_detail_id[$i],
                'product_id' => $request->product_id[$i],
                'quantity' => $request->quantity[$i],
                'note' => $request->note[$i]
            ]);


            $manufacturerNoteDetail = ManufacturerNoteDetail::find($request->manufacturer_note_detail_id[$i]);

            $bomProductId = $manufacturerNoteDetail->product_id;
            $quantity = $manufacturerNoteDetail->quantity;

            $bomQuantity = BomDetail::where('product_id', $request->product_id[$i])
                ->whereHas('bom', function (Builder $query) use ($bomProductId) {
                    $query->where('product_id', '=', $bomProductId);
                })
                ->first()->quantity;

            if (ceil($quantity * $bomQuantity) == $request->quantity[$i]) {
                $manufacturerNoteDetail->update(['status' => 9]);
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
        for ($i = 0; $i < count($request->code); $i++) {
            ShapeNoteDetail::updateOrCreate(
                [
                    'manufacturer_note_detail_id' => $request->manufacturer_note_detail_id[$i],
                ],
                [
                    'shape_note_id' => $shapeNote->id,
                    'contract_detail_id' => $request->contract_detail_id[$i],
                    'product_id' => $request->product_id[$i],
                    'quantity' => $request->quantity[$i],
                    'note' => $request->note[$i]
                ]
            );
        }

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
