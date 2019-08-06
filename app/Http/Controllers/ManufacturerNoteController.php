<?php

namespace App\Http\Controllers;

use App\ManufacturerNote;
use App\ManufacturerNoteDetail;
use App\ManufacturerOrder;
use App\ManufacturerOrderDetail;
use App\Step;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        $manufacturerNoteDetails = ManufacturerNoteDetail::with(
            'contractDetail.manufacturerOrderDetail.manufacturerOrder',
            'contractDetail.price.product',
            'manufacturerNote'
            )
            ->get();
        return view('manufacturer-note.index', compact('manufacturerNoteDetails'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(ManufacturerOrder $manufacturerOrder)
    {
        $manufacturerOrder->update(['status' => 9]);
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
        $manufacturerNote->update($request->all());

        for ($i = 0; $i < count($request->contract_detail_id); $i++) {
            ManufacturerNoteDetail::updateOrCreate(
                [
                    'id' => $request->manufacturer_note_detail_id[$i]
                ],
                [
                    'manufacturer_note_id' => $manufacturerNote->id,
                    'contract_detail_id' => $request->contract_detail_id[$i],
                    'bom_detail_id' => $request->bom_detail_id[$i],
                    'product_id' => $request->product_id[$i],
                    'quantity' => $request->quantity[$i],
                    'note' => $request->note[$i],
                ]
            );
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
        $manufacturerNote->delete();
        flash('Đã xóa phiếu sản xuất' . $manufacturerNote->number);
        return redirect()->route('manufacturer-notes.index');
    }

    public function getByStep(Request $request)
    {
        $query = DB::table('contract_details')
            ->join('manufacturer_order_details','contract_details.id','manufacturer_order_details.contract_detail_id')
            ->join('manufacturer_orders', 'manufacturer_orders.id', 'manufacturer_order_details.manufacturer_order_id')
            ->select('contract_details.id', 'manufacturer_orders.number', 'contract_details.price_id');
        if ($request->stepId == 1) {
            $results = DB::table('manufacturer_note_details')
                ->joinSub($query,'manufacturer','manufacturer.id','=','manufacturer_note_details.contract_detail_id')
                ->join('products', 'products.id','manufacturer_note_details.product_id')
                ->select('manufacturer_note_details.contract_detail_id', 'manufacturer_note_details.quantity', 'products.name', 'products.code', 'manufacturer.number', 'products.id')
                ->get();
        } else {
            $stepBefore = Step::where('id', $request->stepId)->first()->step_id;
            $results = DB::table('step_note_details')
                ->joinSub($query,'manufacturer','manufacturer.id','=','step_note_details.contract_detail_id')
                ->join('step_notes', 'step_notes.id','step_note_details.step_note_id')
                ->join('prices','manufacturer.price_id','=','prices.id')
                ->join('products','products.id','prices.product_id')
                ->where('step_notes.step_id',$stepBefore)
                ->select('step_note_details.contract_detail_id', 'step_note_details.quantity', 'products.name', 'products.code', 'manufacturer.number', 'products.id')
                ->get();
        }

        return response()->json($results);
    }
}
