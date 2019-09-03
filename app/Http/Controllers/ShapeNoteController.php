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
        $rules = [];
        $messages = [];

        foreach ($request->details as $key => $val) {

            $manufacturerNoteDetail = ManufacturerNoteDetail::find($val['manufacturer_note_detail_id']);

            $quantity = $manufacturerNoteDetail->quantity;

            $bomQuantity = $val['bom_detail_quantity'];

            $doneQuantity = ShapeNoteDetail::where('contract_detail_id', $val['contract_detail_id'])
                ->sum('quantity');

            $remainQuantity = ceil($quantity*$bomQuantity) - $doneQuantity;

            $rules['details.' . $key . '.quantity'] = 'required|integer|max:' . $remainQuantity;
            $messages['details.'.$key.'.quantity.max'] = 'Số lượng vượt quá thực tế';
        }

        $request->validate($rules, $messages);

        $shapeNote = ShapeNote::create($request->all());

        $sumQuantityOfRequest = 0;

        foreach ($request->details as $detail) {
            foreach ($request->details as $item) {
                if ($detail['contract_detail_id'] == $item['contract_detail_id'] && $detail['product_id'] == $item['product_id']) {
                    $sumQuantityOfRequest += $item['quantity'];
                }
            }
            $shapeNote->shapeNoteDetails()->create($detail);

            $manufacturerNoteDetail = ManufacturerNoteDetail::find($detail['manufacturer_note_detail_id']);
            $manufacturerNoteDetail->manufacturerNote()->update(['status' => 9]);

            $quantity = $manufacturerNoteDetail->quantity;

            $bomQuantity = $detail['bom_detail_quantity'];

            $doneQuantity = ShapeNoteDetail::where('contract_detail_id', $val['contract_detail_id'])
                ->sum('quantity');

            if (ceil($quantity * $bomQuantity) - $doneQuantity<= $sumQuantityOfRequest) {
                $manufacturerNoteDetail->contractDetail()->update(['status' => 8]);
                $manufacturerNoteDetail->update(['status' => 0]);
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
     * @throws
     */
    public function destroy(ShapeNote $shapeNote)
    {
        $shapeNote->delete();
        flash('Đã xoá phiếu cắt tấm số' . $shapeNote->number, 'warning');

        return redirect()->route('shape-notes.index');
    }
}
