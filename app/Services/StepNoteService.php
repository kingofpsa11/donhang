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
use App\StepNoteDetail;
use Illuminate\Database\Eloquent\Builder;
use App\Http\Requests\StepNoteRequest;

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

    public function create(StepNoteRequest $request)
    {
        $rules = [];
        $messages = [];

        if ($request->step_id == 1 ) {
            foreach ($request->details as $key => $val) {
                $quantityOfStepNote = $this->stepNoteDetailRepository
                    ->getTotal($val['contract_detail_id'], $val['product_id'], $request->step_id);

                $quantityOfShapeNote = ShapeNoteDetail::selectRaw('contract_detail_id, product_id, sum(quantity) AS total')
                    ->groupBy('contract_detail_id', 'product_id')
                    ->where('contract_detail_id', $val['contract_detail_id'])
                    ->where('product_id', $val['product_id'])
                    ->first()->total;

                $remainQuantity = $quantityOfShapeNote - $quantityOfStepNote;

                $rules['details.' . $key . '.quantity'] = 'required|integer|max:' . $remainQuantity;
                $messages['details.'.$key.'.quantity.max'] = 'Số lượng vượt quá thực tế';
            }
        } elseif ($request->step_id == 2 ) {
            foreach ($request->details as $key => $val) {
                $bomQuantity = ShapeNoteDetail::where('contract_detail_id', $val['contract_detail_id'])
                    ->first()->bom_detail_quantity;

                $quantity = $this->stepNoteDetailRepository
                    ->where('contract_detail_id', $val['contract_detail_id'])
                    ->whereHas('stepNote', function (Builder $query) use ($request) {
                        $query->where('step_id', '=', $request->get('step_id') - 1);
                    })
                    ->sum('quantity');

                $doneQuantity = $this->stepNoteDetailRepository
                    ->where('contract_detail_id', $val['contract_detail_id'])
                    ->whereHas('stepNote', function (Builder $query) use ($request) {
                        $query->where('step_id', '=', $request->get('step_id'));
                    })
                    ->sum('quantity');

                $remainQuantity = $quantity/$bomQuantity - $doneQuantity;

                $rules['details.' . $key . '.quantity'] = 'required|integer|max:' . $remainQuantity;
                $messages['details.'.$key.'.quantity.max'] = 'Số lượng vượt quá thực tế';
            }
        } elseif ($request->get('step_id') == 3) {
            foreach ($request->get('details') as $key => $val) {
                $quantity = $this->stepNoteDetailRepository
                    ->where('contract_detail_id', $val['contract_detail_id'])
                    ->whereHas('stepNote', function (Builder $query) use ($request) {
                        $query->where('step_id', '=', $request->get('step_id') - 1);
                    })
                    ->sum('quantity');

                $doneQuantity = $this->stepNoteDetailRepository
                    ->where('contract_detail_id', $val['contract_detail_id'])
                    ->whereHas('stepNote', function (Builder $query) use ($request) {
                        $query->where('step_id', '=', $request->get('step_id'));
                    })
                    ->sum('quantity');

                $remainQuantity = $quantity - $doneQuantity;

                $rules['details.' . $key . '.quantity'] = 'required|integer|max:' . $remainQuantity;
                $messages['details.'.$key.'.quantity.max'] = 'Số lượng vượt quá thực tế';
            }
        }

//        $request->validate($rules, $messages);

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
            $stepNoteDetail = $stepNote->stepNoteDetails()->create($detail);

            if ($request->step_id == 1) {
                $quantityOfStepNote = $this->stepNoteDetailRepository
                    ->getTotal($detail['contract_detail_id'], $detail['product_id'], $request->step_id);

                $quantityOfShapeNote = ShapeNoteDetail::selectRaw('contract_detail_id, product_id, sum(quantity) AS total')
                    ->groupBy('contract_detail_id', 'product_id')
                    ->where('contract_detail_id', $detail['contract_detail_id'])
                    ->where('product_id', $detail['product_id'])
                    ->first()->total;

                if ($quantityOfShapeNote == $quantityOfStepNote) {
                    ShapeNoteDetail::where('contract_detail_id', $detail['contract_detail_id'])
                        ->where('product_id', $detail['product_id'])
                        ->update(['status' => 0]);
                }
            } elseif ($request->step_id == 2) {
                $bomQuantity = ShapeNoteDetail::where('contract_detail_id', $detail['contract_detail_id'])
                    ->first()->bom_detail_quantity;

                $productId = ShapeNoteDetail::where('contract_detail_id', $detail['contract_detail_id'])
                    ->first()->product_id;

                $quantityOfBeforeStepNote = $this->stepNoteDetailRepository
                    ->getTotal($detail['contract_detail_id'], $productId, $request->step_id - 1);

                $quantityOfStepNote = $this->stepNoteDetailRepository
                    ->getTotal($detail['contract_detail_id'], $detail['product_id'], $request->step_id);

                if ($quantityOfBeforeStepNote/$bomQuantity == $quantityOfStepNote) {
                    $stepNoteDetails = StepNoteDetail::where('contract_detail_id', $detail['contract_detail_id'])
                        ->where('product_id', $productId)
                        ->get();
                    foreach ($stepNoteDetails as $value) {
                        $value->update(['status' => 0]);
                    }
                }
            }

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

    public function update(StepNoteRequest $request, $id)
    {
        $stepNote = $this->stepNoteRepository->find($id);

    //Validate
        $rules = [];
        $messages = [];

        if ($request->get('step_id') > 1) {
            foreach ($request->get('details') as $key => $val) {
                $oldQuantity = $stepNote->stepNoteDetails()->find($val['id'])->quantity;

                $quantity = $this->stepNoteDetailRepository
                    ->where('contract_detail_id', $val['contract_detail_id'])
                    ->where('product_id', $val['product_id'])
                    ->whereHas('stepNote', function (Builder $query) use ($request) {
                        $query->where('step_id', '=', $request->get('step_id') - 1);
                    })
                    ->sum('quantity');

                $doneQuantity = $this->stepNoteDetailRepository
                    ->where('contract_detail_id', $val['contract_detail_id'])
                    ->where('product_id', $val['product_id'])
                    ->whereHas('stepNote', function (Builder $query) use ($request) {
                        $query->where('step_id', '=', $request->get('step_id'));
                    })
                    ->sum('quantity');

                $remainQuantity = $quantity + $oldQuantity - $doneQuantity;
                if ($remainQuantity > 0) {
                    $rules['details.' . $key . '.quantity'] = 'required|integer|max:' . $remainQuantity;
                    $messages['details.' . $key . '.quantity.max'] = 'Số lượng vượt quá thực tế';
                }
            }
        }

        $request->validate($rules, $messages);

    //Validate

        $stepNote->update($request->all());
        $this->stepNoteRepository->update($id, $request->all());
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