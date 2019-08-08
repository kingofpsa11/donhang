<?php

namespace App\Repositories;

use App\StepNote;

class StepNoteRepository
{
    protected $stepNote;

    public function __construct(StepNote $stepNote)
    {
        $this->stepNote = $stepNote;
    }


}