<?php

namespace App\Http\Controllers;

use App\ManufacturerNote;
use App\ManufacturerNoteDetail;
use App\ManufacturerOrder;
use App\ManufacturerOrderDetail;
use Illuminate\Http\Request;

class ManufacturerNoteController extends Controller
{

    protected $manufacturerNote;

    public function __construct(ManufacturerNote $manufacturerNote)
    {
        $this->manufacturerNote = $manufacturerNote;
    }

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
        $this->manufacturerNote->fill($request->all())->save();

        for ($i = 0; $i < count($request->contract_detail_id); $i++) {
            ManufacturerNoteDetail::create([
                'manufacturer_note_id' => $this->manufacturerNote->id,
                'contract_detail_id' => $request->contract_detail_id[$i],
                'bom_detail_id' => $request->bom_detail_id[$i],
                'product_id' => $request->product_id[$i],
                'quantity' => $request->quantity[$i],
                'note' => $request->note[$i],
            ]);
        }

        return redirect()->route('manufacturer-notes.show', $this->manufacturerNote);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\ManufacturerNote  $manufacturerNote
     * @return \Illuminate\Http\Response
     */
    public function show(ManufacturerNote $manufacturerNote)
    {

        $manufacturerNote->load('manufacturerNoteDetails.contractDetail.manufacturerOrderDetail.manufacturerOrder');
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
        return $manufacturerNote->manufacturerNoteDetails->first()->bomDetail->product->boms;
        $manufacturerNote->load('manufacturerNoteDetails.contractDetail.contract.contractDetails', 'manufacturerNoteDetails.bomDetail.product.boms.bomDetails.product');
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
        $manufacturerNote->date = $request->date;

        if ($manufacturerNote->save()) {
            foreach ($request->manufacturerNoteDetails as $value) {
                if (isset($value['id'])) {
                    $manufacturerNoteDetail = ManufacturerNoteDetail::find($value['id']);
                    $manufacturerNoteDetail->manufacturer_note_id = $manufacturerNote->id;
                    $manufacturerNoteDetail->contract_detail_id = $value['contract_detail_id'];
                    $manufacturerNoteDetail->bom_detail_id = $value['bom_detail_id'];
                    $manufacturerNoteDetail->product_id = $value['product_id'];
                    $manufacturerNoteDetail->quantity = $value['quantity'];
                    $manufacturerNoteDetail->save();
                } else {
                    $manufacturerNoteDetail = new ManufacturerNoteDetail();
                    $manufacturerNoteDetail->manufacturer_note_id = $manufacturerNote->id;
                    $manufacturerNoteDetail->contract_detail_id = $value['contract_detail_id'];
                    $manufacturerNoteDetail->bom_detail_id = $value['bom_detail_id'];
                    $manufacturerNoteDetail->product_id = $value['product_id'];
                    $manufacturerNoteDetail->quantity = $value['quantity'];
                    $manufacturerNoteDetail->save();
                }
            }
        }
        return view('manufacturer-note.show', compact('manufacturerNote'));
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
