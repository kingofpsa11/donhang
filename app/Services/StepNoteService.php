<?php

namespace App\Services;

use App\Repositories\StepNoteDetailRepository;
use App\Repositories\StepNoteRepository;
use Illuminate\Http\Request;

class StepNoteService
{
    protected $stepNoteRepository;
    protected $stepNoteDetailRepository;

    public function __construct(StepNoteRepository $stepNoteRepository, StepNoteDetailRepository $stepNoteDetailRepository)
    {
        $this->stepNoteRepository = $stepNoteRepository;
        $this->stepNoteDetailRepository = $stepNoteDetailRepository;
    }

    public function all()
    {
        return $this->stepNoteRepository->all();
    }

    public function allWithDetails()
    {
        return $this->stepNoteDetailRepository->all();
    }

    public function create(Request $request)
    {
        $stepNote = $this->stepNoteRepository->create($request->all());

        for ($i = 0; $i < count($request->code); $i++) {
            $this->stepNoteDetailRepository->create($request, $stepNote->id, $i);
        }

        return $stepNote;
    }

    public function find($id)
    {
        return $this->stepNoteRepository->find($id,
            [
                'stepNoteDetails.contractDetail.manufacturerOrderDetail.manufacturerOrder',
                'stepNoteDetails.contractDetail.price.product',
                'step'
            ]);
    }
}