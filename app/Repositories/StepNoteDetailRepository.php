<?php

namespace App\Repositories;

use App\StepNoteDetail;

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
}