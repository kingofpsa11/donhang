<?php

namespace App\Http\Controllers;

use App\ManufacturerNote;
use App\ManufacturerNoteDetail;
use App\ManufacturerOrder;
use App\ManufacturerOrderDetail;
use App\ShapeNoteDetail;
use App\Step;
use App\StepNoteDetail;
use Illuminate\Database\Eloquent\Builder;
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
    public function create()
    {
        $newNumber = ManufacturerNote::getNewNumber();
        return view('manufacturer-note.create', compact('newNumber'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $manufacturerNote = $this->manufacturerNote->fill($request->all());
        $manufacturerNote->save();

        foreach ($request->details as $detail) {
            $manufacturerNoteDetail = $manufacturerNote->manufacturerNoteDetails()->create($detail);
            $manufacturerNoteDetail->contractDetail->update(['status' => 9]);
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

        foreach ($request->details as $detail) {
            $id = $detail['id'];
            unset($detail['id']);
            $manufacturerNoteDetail = $manufacturerNote->manufacturerNoteDetails()->updateOrCreate(['id' => $id], $detail);
            $manufacturerNoteDetail->contractDetail->update(['status' => 9]);
        }

        return redirect()->route('manufacturer-notes.show', $manufacturerNote);
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
        flash('Đã xóa phiếu cắt phôi số ' . $manufacturerNote->number);
        return redirect()->route('manufacturer-notes.index');
    }

    public function getManufacturerNote(Request $request)
    {
        $search = $request->search;

        $result = DB::table('manufacturer_note_details AS mnd')
            ->join('products AS p', 'p.id', '=', 'mnd.product_id')
            ->join('manufacturer_order_details AS mod', 'mod.contract_detail_id', '=', 'mnd.contract_detail_id')
            ->join('manufacturer_orders AS mo', 'mo.id', '=', 'mod.manufacturer_order_id')
            ->where('mo.number', 'LIKE', '%' . $search . '%')
            ->orWhere('p.name', 'LIKE', '%' . $search . '%')
            ->select('mo.number', 'p.name', 'mnd.contract_detail_id', 'mnd.product_id', 'mnd.id', 'mnd.quantity')
            ->get();

        return response()->json($result);
    }

    public function getByStep(Request $request)
    {
//        $contractDetails = DB::table('contract_details AS c')
//            ->join('manufacturer_order_details AS md','md.contract_detail_id','c.id')
//            ->join('manufacturer_orders AS m', 'm.id','md.manufacturer_order_id')
//            ->select('c.id', 'm.number');
//
//        if ($request->stepId == 1) {
//
//            $results = DB::table('shape_note_details AS snd')
//                ->join('shape_notes AS sn', 'sn.id', '=', 'snd.shape_note_id')
//                ->joinSub($contractDetails,'manufacturer','manufacturer.id','=','snd.contract_detail_id')
//                ->join('products', 'products.id','snd.product_id')
//                ->where('snd.status', 10)
//                ->select('manufacturer.number', 'snd.quantity as remain_quantity', 'products.name', 'products.code', 'snd.product_id', 'manufacturer.id AS contract_detail_id', 'snd.id as note_detail_id')
//                ->get();
//
//        } elseif ($request->stepId == 2) {
//
//            $queryStepAfter = DB::table('step_notes')
//                ->join('step_note_details', 'step_notes.id', 'step_note_details.step_note_id')
//                ->select('step_note_details.product_id', 'step_note_details.contract_detail_id', 'step_notes.step_id', DB::raw('SUM(step_note_details.quantity) AS total_quantity'))
//                ->groupBy('step_note_details.product_id', 'step_note_details.contract_detail_id', 'step_notes.step_id')
//                ->having('step_notes.step_id', '=', $request->stepId);
//
//            $queryStepBefore = DB::table('step_notes')
//                ->join('step_note_details', 'step_notes.id', 'step_note_details.step_note_id')
//                ->select('step_note_details.product_id', 'step_note_details.contract_detail_id', 'step_notes.step_id', DB::raw('SUM(step_note_details.quantity) AS total_quantity'))
//                ->groupBy('step_note_details.product_id', 'step_note_details.contract_detail_id', 'step_notes.step_id')
//                ->having('step_notes.step_id', '=', $request->stepId - 1);
//
//            $results = DB::table('manufacturer_note_details')
//                ->join('manufacturer_order_details', 'manufacturer_note_details.contract_detail_id', 'manufacturer_order_details.contract_detail_id')
//                ->join('manufacturer_orders', 'manufacturer_orders.id', 'manufacturer_order_details.manufacturer_order_id')
//                ->leftJoinSub($queryStepAfter, 'stepDetailAfter', function ($join) {
//                    $join->on('stepDetailAfter.contract_detail_id', '=', 'manufacturer_note_details.contract_detail_id');
//                })
//                ->joinSub($queryStepBefore, 'stepDetailBefore', function ($join) use ($request) {
//                    $join->on('stepDetailBefore.contract_detail_id', '=', 'manufacturer_note_details.contract_detail_id')
//                        ->where('stepDetailBefore.step_id', 1);
//                })
//                ->join('products', 'products.id', 'manufacturer_note_details.product_id')
//                ->select(
//                    'manufacturer_note_details.contract_detail_id',
//                    'products.name', 'products.code',
//                    'manufacturer_orders.number',
//                    'manufacturer_note_details.product_id',
//                    DB::raw('
//                        IF(manufacturer_note_details.quantity > stepDetailBefore.total_quantity,
//                            stepDetailBefore.total_quantity - IFNULL(stepDetailAfter.total_quantity, 0),
//                            manufacturer_note_details.quantity - IFNULL(stepDetailAfter.total_quantity, 0))
//                        AS remain_quantity'))
//                ->having('remain_quantity', '>', 0)
//                ->get();
//
//        } elseif ($request->stepId == 3) {
//
//            $queryStepAfter = DB::table('step_notes')
//                ->join('step_note_details', 'step_notes.id', 'step_note_details.step_note_id')
//                ->where('step_notes.step_id', $request->stepId)
//                ->select('step_note_details.product_id', 'step_note_details.contract_detail_id', 'step_notes.step_id', DB::raw('SUM(step_note_details.quantity) AS total_quantity'))
//                ->groupBy('step_note_details.product_id', 'step_note_details.contract_detail_id', 'step_notes.step_id');
//
//            $results = DB::table('step_notes')
//                ->join('step_note_details', 'step_notes.id', 'step_note_details.step_note_id')
//                ->where('step_notes.step_id', $request->stepId - 1)
//                ->leftJoinSub($queryStepAfter, 'sa', function ($join) {
//                    $join->on('sa.contract_detail_id', '=', 'step_note_details.contract_detail_id')
//                        ->on('sa.product_id', '=', 'step_note_details.product_id');
//                })
//                ->joinSub($contractDetails, 'c', function ($join) {
//                    $join->on('c.id', '=', 'step_note_details.contract_detail_id');
//                })
//                ->join('products as p', 'p.id', 'step_note_details.product_id')
//                ->select(
//                    'step_note_details.product_id',
//                    'step_note_details.contract_detail_id',
//                    'step_notes.step_id',
//                    'p.name', 'p.code',
//                    'c.number',
//                    DB::raw('(SUM(step_note_details.quantity) - IFNULL(sa.total_quantity, 0)) AS remain_quantity'))
//                ->groupBy(
//                    'step_note_details.product_id',
//                    'step_note_details.contract_detail_id',
//                    'step_notes.step_id',
//                    'p.name', 'p.code',
//                    'c.number',
//                    'sa.total_quantity'
//                )
//                ->having('remain_quantity', '>', 0)
//                ->get();
//
//        } elseif ($request->stepId == 4) {
//
//            $queryStepAfter = DB::table('step_notes')
//                ->join('step_note_details', 'step_notes.id', 'step_note_details.step_note_id')
//                ->join('contract_details', 'contract_details.id', 'step_note_details.contract_detail_id')
//                ->join('prices', 'prices.id','contract_details.price_id')
//                ->where('step_notes.step_id', $request->stepId)
//                ->select('prices.product_id', 'step_note_details.contract_detail_id', 'step_notes.step_id', DB::raw('SUM(step_note_details.quantity) AS total_quantity'))
//                ->groupBy('prices.product_id', 'step_note_details.contract_detail_id', 'step_notes.step_id');
//
//            $results = DB::table('step_notes')
//                ->join('step_note_details', 'step_notes.id', 'step_note_details.step_note_id')
//                ->join('contract_details', 'contract_details.id', 'step_note_details.contract_detail_id')
//                ->join('prices', 'prices.id','contract_details.price_id')
//                ->where('step_notes.step_id', $request->stepId - 1)
//                ->leftJoinSub($queryStepAfter, 'sa', function ($join) {
//                    $join->on('sa.contract_detail_id', '=', 'step_note_details.contract_detail_id');
//                })
//                ->joinSub($contractDetails, 'c', function ($join) {
//                    $join->on('c.id', '=', 'step_note_details.contract_detail_id');
//                })
//                ->join('products as p', 'p.id', 'prices.product_id')
//                ->select(
//                    'prices.product_id',
//                    'step_note_details.contract_detail_id',
//                    'step_notes.step_id',
//                    'p.name', 'p.code',
//                    'c.number',
//                    DB::raw('(SUM(step_note_details.quantity) - IFNULL(sa.total_quantity, 0)) AS remain_quantity'))
//                ->groupBy(
//                    'prices.product_id',
//                    'step_note_details.contract_detail_id',
//                    'step_notes.step_id',
//                    'p.name', 'p.code',
//                    'c.number',
//                    'sa.total_quantity'
//                )
//                ->having('remain_quantity', '>', 0)
//                ->get();
//        }
//
//        return response()->json($results);
        $results = null;
        $newResult = [];
        switch ($request->stepId) {
            case 1:
                //
                $results = ShapeNoteDetail::where('status', 10)
                    ->select('id', 'contract_detail_id', 'product_id', 'quantity')
                    ->with(
                        'contractDetail:id',
                        'contractDetail.manufacturerOrderDetail.manufacturerOrder:id,number',
                        'product:id,name,code')
                    ->get();
                break;
            case 2:
                $results = StepNoteDetail::where('status', 10)
                    ->whereHas('stepNote', function ($query) {
                            $query->where('step_id', '=', 1);
                        })
                    ->with([
                        'product:id,name,code',
                        'contractDetail.manufacturerOrderDetail.manufacturerOrder:id,number',
                    ])
                    ->groupBy('contract_detail_id', 'product_id', 'id')
                    ->selectRaw('contract_detail_id, product_id, id, sum(quantity) as total')
                    ->get();

                $after = StepNoteDetail::whereHas('stepNote', function (Builder $query) {
                        $query->where('step_id', '=', 2);
                    })
                    ->groupBy('contract_detail_id')
                    ->selectRaw('contract_detail_id, sum(quantity) as total')
                    ->get();
                break;
            default:
                break;
        }

        foreach ($results as $result) {
            $newResult[] = [
                'name' => $result->product->name,
                'code' => $result->product->code,
                'note_detail_id' => $result->id,
                'number' => $result->contractDetail->manufacturerOrderDetail->manufacturerOrder->number,
                'remain_quantity' => $result->total - ($after->where('contract_detail_id', $result->contract_detail_id)->first()->total ?? 0),
                'product_id' => $result->product_id,
                'contract_detail_id' => $result->contract_detail_id,
            ];
        }
        return response()->json($newResult);
    }
}
