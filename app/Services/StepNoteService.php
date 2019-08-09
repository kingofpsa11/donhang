<?php

namespace App\Services;

use App\Repositories\StepNoteDetailRepository;
use App\Repositories\StepNoteRepository;

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
}