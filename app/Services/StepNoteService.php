<?php

namespace App\Services;

use App\GoodDelivery;
use App\GoodReceive;
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
//        $goodDelivery = $stepNote->delivery()->firstOrCreate(
//            [
//                'deliverable_id' => $stepNote->id,
//            ],
//            [
//                'number' => GoodDelivery::getNewNumber(),
//                'customer_id' => 941,
//                'date' => date('d/m/Y')
//            ]);

        $goodReceive = $stepNote->receive()->firstOrCreate(
            [
                'receivable_id' => $stepNote->id,
            ],
            [
                'number' => GoodReceive::getNewNumber(),
                'supplier_id' => 941,
                'date' => $stepNote->date,
            ]
        );

        for ($i = 0; $i < count($request->code); $i++) {
            $stepNoteDetail = $this->stepNoteDetailRepository->create($request, $stepNote->id, $i);
//            $stepNoteDetail->deliveries()->firstOrCreate(
//                [
//                    'deliverable_id' => $stepNoteDetail->id,
//                ],
//                [
//                    'good_delivery_id' => $goodDelivery->id,
//                    'product_id' => $stepNoteDetail->contractDetail->price->product_id,
//                    'quantity' => $stepNoteDetail->quantity,
//                ]
//            );

            $stepNoteDetail->receive()->firstOrCreate(
                [
                    'receivable_id' => $stepNoteDetail->id,
                ],
                [
                    'good_receive_id' => $goodReceive->id,
                    'product_id' => $stepNoteDetail->contractDetail->price->product_id,
                    'quantity' => $stepNoteDetail->quantity,
                    'store_id' => 27,
                ]
            );
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
        $stepNote = $this->stepNoteRepository->find($id);

        $this->stepNoteDetailRepository->update(['status' => 9], $id);
        $goodDelivery = $stepNote->delivery()->firstOrCreate(
            [
                'deliverable_id' => $stepNote->id,
            ],
            [
                'number' => GoodDelivery::getNewNumber(),
                'customer_id' => 941,
                'date' => date('d/m/Y')
            ]);
        $goodDelivery->goodDeliveryDetails()->update(['status' => 9]);

        for ($i = 0; $i < count($request->code); $i++) {
            $stepNoteDetail = $this->stepNoteDetailRepository->updateOrCreate(
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

            $stepNoteDetail->delivery()->updateOrCreate(
                [
                    'deliverable_id' => $stepNoteDetail->id,
                ],
                [
                    'good_delivery_id' => $goodDelivery->id,
                    'product_id' => $stepNoteDetail->contractDetail->price->product->id,
                    'quantity' => $stepNoteDetail->quantity,
                    'status' => 10,
                ]
            );
        }

        $goodDelivery->goodDeliveryDetails()->where('status', 9)->delete();
        $this->stepNoteDetailRepository->deleteWhere(['status' => 9]);

        return $stepNote;
    }

    public function delete($id)
    {
        $this->stepNoteRepository->delete($id);
    }
}