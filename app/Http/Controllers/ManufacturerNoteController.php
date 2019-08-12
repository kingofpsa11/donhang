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
        $contractDetails = DB::table('contract_details AS c')
            ->join('manufacturer_order_details AS md','md.contract_detail_id','c.id')
            ->join('manufacturer_orders AS m', 'm.id','md.manufacturer_order_id')
            ->select('c.id', 'm.number');

        if ($request->stepId == 1) {

            $results = DB::table('manufacturer_note_details')
                ->joinSub($contractDetails,'manufacturer','manufacturer.id','=','manufacturer_note_details.contract_detail_id')
                ->join('products', 'products.id','manufacturer_note_details.product_id')
                ->select('manufacturer_note_details.contract_detail_id', 'manufacturer_note_details.quantity as remain_quantity', 'products.name', 'products.code', 'manufacturer.number', 'manufacturer_note_details.product_id')
                ->get();

        } elseif ($request->stepId == 2) {

            $queryStepAfter = DB::table('step_notes')
                ->join('step_note_details', 'step_notes.id', 'step_note_details.step_note_id')
                ->select('step_note_details.product_id', 'step_note_details.contract_detail_id', 'step_notes.step_id', DB::raw('SUM(step_note_details.quantity) AS total_quantity'))
                ->groupBy('step_note_details.product_id', 'step_note_details.contract_detail_id', 'step_notes.step_id')
                ->having('step_notes.step_id', '=', $request->stepId);

            $queryStepBefore = DB::table('step_notes')
                ->join('step_note_details', 'step_notes.id', 'step_note_details.step_note_id')
                ->select('step_note_details.product_id', 'step_note_details.contract_detail_id', 'step_notes.step_id', DB::raw('SUM(step_note_details.quantity) AS total_quantity'))
                ->groupBy('step_note_details.product_id', 'step_note_details.contract_detail_id', 'step_notes.step_id')
                ->having('step_notes.step_id', '=', $request->stepId - 1);

            $bomDetails = DB::table('bom_details')
                ->join('products', 'products.id', 'bom_details.product_id')
                ->select('bom_details.id', 'products.code', 'products.name', 'products.id as product_id');

            $results = DB::table('manufacturer_note_details')
                ->join('manufacturer_order_details', 'manufacturer_note_details.contract_detail_id', 'manufacturer_order_details.contract_detail_id')
                ->join('manufacturer_orders', 'manufacturer_orders.id', 'manufacturer_order_details.manufacturer_order_id')
                ->leftJoinSub($queryStepAfter, 'stepDetailAfter', function ($join) {
                    $join->on('stepDetailAfter.contract_detail_id', '=', 'manufacturer_note_details.contract_detail_id');
                })
                ->joinSub($queryStepBefore, 'stepDetailBefore', function ($join) use ($request) {
                    $join->on('stepDetailBefore.contract_detail_id', '=', 'manufacturer_note_details.contract_detail_id')
                        ->where('stepDetailBefore.step_id', 1);
                })
                ->joinSub($bomDetails, 'bomDetails', 'bomDetails.id','=', 'manufacturer_note_details.bom_detail_id')
                ->select(
                    'manufacturer_note_details.contract_detail_id',
                    'bomDetails.name', 'bomDetails.code',
                    'manufacturer_orders.number',
                    'bomDetails.product_id',
                    DB::raw('
                    IF(manufacturer_note_details.quantity > stepDetailBefore.total_quantity,
                        stepDetailBefore.total_quantity - IFNULL(stepDetailAfter.total_quantity, 0),
                        manufacturer_note_details.quantity - IFNULL(stepDetailAfter.total_quantity, 0))
                    AS remain_quantity'))
                ->having('remain_quantity', '>', 0)
                ->get();

        } elseif ($request->stepId == 3) {

            $queryStepAfter = DB::table('step_notes')
                ->join('step_note_details', 'step_notes.id', 'step_note_details.step_note_id')
                ->where('step_notes.step_id', $request->stepId)
                ->select('step_note_details.product_id', 'step_note_details.contract_detail_id', 'step_notes.step_id', DB::raw('SUM(step_note_details.quantity) AS total_quantity'))
                ->groupBy('step_note_details.product_id', 'step_note_details.contract_detail_id', 'step_notes.step_id');

            $results = DB::table('step_notes')
                ->join('step_note_details', 'step_notes.id', 'step_note_details.step_note_id')
                ->where('step_notes.step_id', $request->stepId - 1)
                ->leftJoinSub($queryStepAfter, 'sa', function ($join) {
                    $join->on('sa.contract_detail_id', '=', 'step_note_details.contract_detail_id')
                        ->on('sa.product_id', '=', 'step_note_details.product_id');
                })
                ->joinSub($contractDetails, 'c', function ($join) {
                    $join->on('c.id', '=', 'step_note_details.contract_detail_id');
                })
                ->join('products as p', 'p.id', 'step_note_details.product_id')
                ->select(
                    'step_note_details.product_id',
                    'step_note_details.contract_detail_id',
                    'step_notes.step_id',
                    'p.name', 'p.code',
                    'c.number',
                    DB::raw('(SUM(step_note_details.quantity) - IFNULL(sa.total_quantity, 0)) AS remain_quantity'))
                ->groupBy(
                    'step_note_details.product_id',
                    'step_note_details.contract_detail_id',
                    'step_notes.step_id',
                    'p.name', 'p.code',
                    'c.number',
                    'sa.total_quantity'
                )
                ->having('remain_quantity', '>', 0)
                ->get();

        } elseif ($request->stepId == 4) {

            $queryStepAfter = DB::table('step_notes')
                ->join('step_note_details', 'step_notes.id', 'step_note_details.step_note_id')
                ->join('contract_details', 'contract_details.id', 'step_note_details.contract_detail_id')
                ->join('prices', 'prices.id','contract_details.price_id')
                ->where('step_notes.step_id', $request->stepId)
                ->select('prices.product_id', 'step_note_details.contract_detail_id', 'step_notes.step_id', DB::raw('SUM(step_note_details.quantity) AS total_quantity'))
                ->groupBy('prices.product_id', 'step_note_details.contract_detail_id', 'step_notes.step_id');

            $results = DB::table('step_notes')
                ->join('step_note_details', 'step_notes.id', 'step_note_details.step_note_id')
                ->join('contract_details', 'contract_details.id', 'step_note_details.contract_detail_id')
                ->join('prices', 'prices.id','contract_details.price_id')
                ->where('step_notes.step_id', $request->stepId - 1)
                ->leftJoinSub($queryStepAfter, 'sa', function ($join) {
                    $join->on('sa.contract_detail_id', '=', 'step_note_details.contract_detail_id');
                })
                ->joinSub($contractDetails, 'c', function ($join) {
                    $join->on('c.id', '=', 'step_note_details.contract_detail_id');
                })
                ->join('products as p', 'p.id', 'prices.product_id')
                ->select(
                    'prices.product_id',
                    'step_note_details.contract_detail_id',
                    'step_notes.step_id',
                    'p.name', 'p.code',
                    'c.number',
                    DB::raw('(SUM(step_note_details.quantity) - IFNULL(sa.total_quantity, 0)) AS remain_quantity'))
                ->groupBy(
                    'prices.product_id',
                    'step_note_details.contract_detail_id',
                    'step_notes.step_id',
                    'p.name', 'p.code',
                    'c.number',
                    'sa.total_quantity'
                )
                ->having('remain_quantity', '>', 0)
                ->get();
        }

        return response()->json($results);
    }
}
