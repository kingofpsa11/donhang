<?php

namespace App\Services;

use App\GoodDelivery;
use App\GoodReceive;
use App\ManufacturerNoteDetail;
use App\Repositories\GoodDeliveryDetailRepository;
use App\Repositories\GoodDeliveryRepository;
use App\Repositories\GoodReceiveDetailRepository;
use App\Repositories\GoodReceiveRepository;
use App\Repositories\StepNoteDetailRepository;
use App\Repositories\StepNoteRepository;
use App\ShapeNoteDetail;
use Illuminate\Database\Eloquent\Builder;
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

    public function getNewNumber()
    {
        return $this->stepNoteRepository->getNewNumber();
    }

    public function create(Request $request)
    {
        if ($request->step_id > 1) {
            foreach ($request->details as $detail) {
                $quantity = $this->stepNoteDetailRepository
                    ->where('contract_detail_id', $detail['contract_detail_id'])
                    ->whereHas('stepNote', function (Builder $query) use ($request) {
                        $query->where('step_id', '=', $request->step_id);
                    })
                    ->sum('quantity');

                $doneQuantity = $this->stepNoteDetailRepository
                    ->where('contract_detail_id', $detail['contract_detail_id'])
                    ->whereHas('stepNote', function (Builder $query) use ($request) {
                        $query->where('step_id', '=', $request->step_id);
                    })
                    ->sum('quantity');

                $remainQuantity = $quantity - $doneQuantity;

                if ($remainQuantity < $detail['quantity'] && $remainQuantity > 0) {
                    flash('Số lượng nhiều hơn', 'warning');
                    return false;
                } elseif ($remainQuantity == $detail['quantity']) {
                    $this->stepNoteDetailRepository
                        ->where('contract_detail_id', $detail['contract_detail_id'])
                        ->whereHas('stepNote', function (Builder $query) use ($request) {
                            $query->where('step_id', '=', $request->step_id - 1);
                        })
                        ->update(['status' => 9]);
                }
            }
        }

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

        foreach ($request->details as $detail) {
            if ($request->step_id == 1) {
                $detail['step_note_id'] = $stepNote->id;
                $stepNoteDetail = ShapeNoteDetail::find($detail['note_detail_id'])->stepables()->create($detail);
            }
//            $stepNoteDetail = $stepNote->stepNoteDetails()->create($detail);

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

            $stepNoteDetail->receive()->create(
                [
                    'good_receive_id' => $goodReceive->id,
                    'product_id' => $detail['product_id'],
                    'quantity' => $stepNoteDetail->quantity,
                    'store_id' => 27,
                ]
            );
        }

        return $stepNote;
    }

    public function update(Request $request, $id)
    {
        $this->stepNoteRepository->update($id, $request->all());
        $stepNote = $this->stepNoteRepository->find($id);
        $beforeStepNote = $this->stepNoteRepository
            ->where('step_id', $request->step_id - 1);

        $this->stepNoteDetailRepository->update(['status' => 9], $id);
//        $goodDelivery = $stepNote->delivery()->firstOrCreate(
//            [
//                'deliverable_id' => $stepNote->id,
//            ],
//            [
//                'number' => GoodDelivery::getNewNumber(),
//                'customer_id' => 941,
//                'date' => $stepNote->date,
//            ]);
//        $goodDelivery->goodDeliveryDetails()->update(['status' => 9]);

        $goodReceive = $stepNote->receive()->updateOrCreate(
            [
                'receivable_id' => $stepNote->id,
            ],
            [
                'number' => GoodReceive::getNewNumber(),
                'supplier_id' => 941,
                'date' => $stepNote->date,
            ]
        );
        $goodReceive->goodReceiveDetails()->update(['status' => 9]);

        for ($i = 0; $i < count($request->code); $i++) {
            $beforeStepNote->stepNoteDetails()->where('contract_detail_id', $request->contract_detail_id[$i])
                ->groupBy('contract_detail_id')
                ->sum('quantity')
                ->get();
            var_dump($beforeStepNote);
            die;
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

//            $stepNoteDetail->delivery()->updateOrCreate(
//                [
//                    'deliverable_id' => $stepNoteDetail->id,
//                ],
//                [
//                    'good_delivery_id' => $goodDelivery->id,
//                    'product_id' => $stepNoteDetail->contractDetail->price->product->id,
//                    'quantity' => $stepNoteDetail->quantity,
//                    'status' => 10,
//                ]
//            );

            $stepNoteDetail->receive()->updateOrCreate(
                [
                    'receivable_id' => $stepNoteDetail->id,
                ],
                [
                    'good_receive_id' => $goodReceive->id,
                    'product_id' => $stepNoteDetail->contractDetail->price->product_id,
                    'quantity' => $stepNoteDetail->quantity,
                    'store_id' => 27,
                    'status' => 10,
                ]
            );
        }

        $goodReceive->goodReceiveDetails()->where('status', 9)->delete();
        $this->stepNoteDetailRepository->deleteWhere(['status' => 9]);

        return $stepNote;
    }

    public function delete($id)
    {
        $goodReceive = $this->stepNoteRepository->find($id)->receive();
        $this->stepNoteRepository->delete($id);
        $goodReceive->delete();
    }
}