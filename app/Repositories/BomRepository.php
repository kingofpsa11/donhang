<?php
namespace App\Repositories;

use App\Bom;

class BomRepository
{
    protected $bom;

    public function __construct(Bom $bom)
    {
        $this->bom = $bom;
    }

    public function create($attributes)
    {
        return $this->bom->create($attributes);
    }

    public function find($id)
    {
        return $this->bom->find($id);
    }

    public function getBomDetails($id) {
        return $this->bom->where('id', $id)->with('bomDetails')->first();
    }
}