<?php
namespace App\Repositories;

use App\GoodReceive;

class GoodReceiveRepository
{
    protected $goodReceive;

    public function __construct(GoodReceive $goodReceive)
    {
        $this->goodReceive = $goodReceive;
    }

    public function create($attributes)
    {
        return $this->goodReceive->create($attributes);
    }

    public function find($id)
    {
        return $this->goodReceive->find($id);
    }

    public function show($id)
    {
        return $this->goodReceive->where('id', $id)
            ->with('goodReceiveDetails.product', 'goodReceiveDetails.bom', 'goodReceiveDetails.store', 'supplier')
            ->first();
    }

    public function update($attributes, $id)
    {
        return $this->goodReceive->find($id)->update($attributes);
    }

    public function getNewNumber()
    {
        return $this->goodReceive->whereYear('date', date('Y'))
                ->max('number') + 1 ?? 1;
    }
}