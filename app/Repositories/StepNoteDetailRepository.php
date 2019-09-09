<?php

namespace App\Repositories;

use App\StepNoteDetail;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

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
                'contract_detail_id' => $request->contract_detail_id[$i]
            ],
            [
                'product_id' => $request->product_id[$i],
                'quantity' => $request->quantity[$i]
            ]
        );
    }

    public function where($column, $operator = null, $value = null, $boolean = 'and')
    {
        return $this->stepNoteDetail->where($column, $operator, $value, $boolean);
    }

    public function getTotal($contractDetailId, $productId, $stepId)
    {
        return $this->stepNoteDetail
            ->whereHas('stepNote', function (Builder $query) use ($stepId) {
                $query->where('step_id', '=', $stepId);
            })
            ->groupBy('contract_detail_id', 'product_id')
            ->selectRaw('contract_detail_id, product_id, sum(quantity) as total')
            ->where('product_id', $productId)
            ->where('contract_detail_id', $contractDetailId)
            ->first()
            ->total ?? 0;
    }

    public function update($attributes, $id)
    {
        return $this->stepNoteDetail->where('step_note_id', $id)->update($attributes);
    }

    public function updateOrCreate(array $attributes, array $values = [])
    {
        return $this->stepNoteDetail->updateOrCreate($attributes, $values);
    }

    public function deleteWhere(array $where)
    {
        return $this->stepNoteDetail->where($where)->delete();
    }
}