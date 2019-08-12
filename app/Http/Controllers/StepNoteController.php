<?php

namespace App\Http\Controllers;

use App\Services\StepNoteService;
use App\Step;
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
        $stepNote = $this->stepNoteService->create($request);

        return redirect()->route('step-notes.show', $stepNote);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\StepNote  $stepNote
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $stepNote = $this->stepNoteService->findWithDetails($id);
        return view('step-note.show', compact('stepNote'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\StepNote  $stepNote
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $steps = Step::all();
        $stepNote = $this->stepNoteService->findWithDetails($id);
        return view('step-note.edit', compact('stepNote', 'steps'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\StepNote  $stepNote
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->stepNoteService->update($request, $id);

        return redirect()->route('step-notes.show', $id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\StepNote  $stepNote
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->stepNoteService->delete();
        flash('Đã xóa phiếu ' . $stepNote->number)->success();
        return redirect()->route('step-notes.index');
    }
}
