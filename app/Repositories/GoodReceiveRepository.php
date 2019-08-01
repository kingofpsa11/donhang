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

    public function show($id)
    {
        return $this->goodReceive->where('id', $id)
            ->with('goodReceiveDetails.product', 'goodReceiveDetails.bom', 'goodReceiveDetails.store', 'supplier')
            ->get();

    }

    public function getNewNumber()
    {
        return $this->goodReceive->whereYear('date', date('Y'))
                ->max('number') + 1 ?? 1;
    }
}