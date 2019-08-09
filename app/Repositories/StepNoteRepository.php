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

    public function all()
    {
        return $this->stepNote->all();
    }
}