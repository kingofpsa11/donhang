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

    public function find($id, array $with = array())
    {
        $query = $this->stepNote->make($with);
        return $query->find($id);
    }

    public function make(array $with = array())
    {
        return $this->stepNote->with($with);
    }

    public function create(array $attributes)
    {
        return $this->stepNote->create($attributes);
    }
}