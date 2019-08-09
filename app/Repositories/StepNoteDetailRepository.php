<?php

namespace App\Repositories;

use App\StepNoteDetail;
use Illuminate\Http\Request;

class StepNoteDetailRepository
{
    protected $stepNoteDetail;

    public function __construct(StepNoteDetail $stepNoteDetail)
    {
        $this->stepNoteDetail = $stepNoteDetail;
    }

    public function all()
    {
        return $this->stepNoteDetail->with(
            'contractDetail.manufacturerOrderDetail.manufacturerOrder',
            'contractDetail.price.product',
            'stepNote.step'
        )
            ->get();
    }

    public function create(Request $request, $id, $i)
    {
        return $this->stepNoteDetail->firstOrCreate([
            'step_note_id' => $id,
            'contract_detail_id' => $request->contract_detail_id[$i],
            'product_id' => $request->product_id[$i],
            'quantity' => $request->quantity[$i]
        ]);
    }
}