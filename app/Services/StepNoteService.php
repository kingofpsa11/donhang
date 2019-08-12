<?php

namespace App\Services;

use App\GoodDelivery;
use App\Repositories\GoodDeliveryDetailRepository;
use App\Repositories\GoodDeliveryRepository;
use App\Repositories\GoodReceiveDetailRepository;
use App\Repositories\GoodReceiveRepository;
use App\Repositories\StepNoteDetailRepository;
use App\Repositories\StepNoteRepository;
use Illuminate\Http\Request;

class StepNoteService
{
    protected $stepNoteRepository;
    protected $stepNoteDetailRepository;
    protected $goodDeliveryRepository;
    protected $goodDeliveryDetailRepository;
    protected $goodReceiveRepository;
    protected $goodReceiveDetailRepository;

    public function __construct(
        StepNoteRepository $stepNoteRepository,
        StepNoteDetailRepository $stepNoteDetailRepository,
        GoodDeliveryRepository $goodDeliveryRepository,
        GoodDeliveryDetailRepository $goodDeliveryDetailRepository,
        GoodReceiveRepository $goodReceiveRepository,
        GoodReceiveDetailRepository $goodReceiveDetailRepository
    )
    {
        $this->stepNoteRepository = $stepNoteRepository;
        $this->stepNoteDetailRepository = $stepNoteDetailRepository;
        $this->goodDeliveryRepository = $goodDeliveryRepository;
        $this->goodDeliveryDetailRepository = $goodDeliveryDetailRepository;
        $this->goodReceiveRepository = $goodReceiveRepository;
        $this->goodReceiveDetailRepository = $goodReceiveDetailRepository;
    }

    public function all()
    {
        return $this->stepNoteRepository->all();
    }

    public function allWithDetails()
    {
        return $this->stepNoteDetailRepository->all();
    }

    public function create(Request $request)
    {
        $stepNote = $this->stepNoteRepository->create($request->all());

        for ($i = 0; $i < count($request->code); $i++) {
            $this->stepNoteDetailRepository->create($request, $stepNote->id, $i);
        }

        return $stepNote;
    }

    public function find($id)
    {
        return $this->stepNoteRepository->find($id);
    }

    public function findWithDetails($id)
    {
        return $this->stepNoteRepository->find($id,
            [
                'stepNoteDetails.contractDetail.manufacturerOrderDetail.manufacturerOrder',
                'stepNoteDetails.contractDetail.price.product',
                'step'
            ]);
    }

    public function update(Request $request, $id)
    {
        $this->stepNoteRepository->update($id, $request->all());
        $this->stepNoteDetailRepository->update(['status' => 9], $id);

        for ($i = 0; $i < count($request->code); $i++) {
            $this->stepNoteDetailRepository->updateOrCreate(
                [
                    'id' => $request->step_note_detail_id[$i]
                ],
                [
                    'step_note_id' => $id,
                    'contract_detail_id' => $request->contract_detail_id[$i],
                    'product_id' => $request->product_id[$i],
                    'quantity' => $request->quantity[$i],
                    'status' => 10
                ]);
        }

        $this->stepNoteDetailRepository->deleteWhere(['status' => 9]);
    }
}