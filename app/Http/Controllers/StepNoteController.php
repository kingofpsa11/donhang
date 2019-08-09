<?php

namespace App\Http\Controllers;

use App\ManufacturerOrder;
use App\Services\StepNoteService;
use App\Step;
use App\StepNoteDetail;
use Illuminate\Http\Request;

class StepNoteController extends Controller
{
    protected $stepNoteService;

    public function __construct(StepNoteService $stepNoteService)
    {
        $this->stepNoteService = $stepNoteService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $stepNoteDetails = $this->stepNoteService->allWithDetails();

        return view('step-note.index', compact('stepNoteDetails'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $steps = Step::all();
        return view('step-note.create', compact('steps'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->stepNote->fill($request->all())->save();

        for ($i = 0; $i < count($request->code); $i++) {
            StepNoteDetail::create([
                'step_note_id' => $this->stepNote->id,
                'contract_detail_id' => $request->contract_detail_id[$i],
                'product_id' => $request->product_id[$i],
                'quantity' => $request->quantity[$i]
            ]);
        }

        return redirect()->route('step-notes.show', $this->stepNote);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\StepNote  $stepNote
     * @return \Illuminate\Http\Response
     */
    public function show(StepNote $stepNote)
    {
        $stepNote->load(
            'stepNoteDetails.contractDetail.manufacturerOrderDetail.manufacturerOrder',
            'stepNoteDetails.contractDetail.price.product',
            'step'
            );
        return view('step-note.show', compact('stepNote'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\StepNote  $stepNote
     * @return \Illuminate\Http\Response
     */
    public function edit(StepNote $stepNote)
    {
        $steps = Step::all();
        $stepNote->load(
            'stepNoteDetails.contractDetail.manufacturerOrderDetail.manufacturerOrder',
            'stepNoteDetails.contractDetail.price.product',
            'step'
        );
        return view('step-note.edit', compact('stepNote', 'steps'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\StepNote  $stepNote
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, StepNote $stepNote)
    {
        $stepNote->fill($request->all())->save();
        $stepNote->stepNoteDetails()->update(['status' => 9]);
        for ($i = 0; $i < count($request->code); $i++) {
            StepNoteDetail::updateOrCreate(
                [
                    'id' => $request->step_note_detail_id[$i]
                ],
                [
                    'step_note_id' => $stepNote->id,
                    'contract_detail_id' => $request->contract_detail_id[$i],
                    'product_id' => $request->product_id[$i],
                    'quantity' => $request->quantity[$i],
                    'status' => 10
                ]);
        }

        $stepNote->stepNoteDetails()->where('status', 9)->delete();

        return redirect()->route('step-notes.show', $this->stepNote);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\StepNote  $stepNote
     * @return \Illuminate\Http\Response
     */
    public function destroy(StepNote $stepNote)
    {
        $stepNote->delete();
        flash('Đã xóa phiếu ' . $stepNote->number)->success();
        return redirect()->route('step-notes.index');
    }
}
