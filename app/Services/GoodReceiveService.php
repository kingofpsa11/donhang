<?php

namespace App\Services;

use Illuminate\Http\Request;
use App\Repositories\GoodDeliveryRepository;
use App\Repositories\GoodReceiveDetailRepository;
use App\Repositories\GoodReceiveRepository;
use App\Repositories\GoodDeliveryDetailRepository;
use App\Repositories\BomRepository;
use App\Role;

class GoodReceiveService
{
    protected $goodReceiveDetailRepository;
    protected $goodReceiveRepository;
    protected $goodDeliveryRepository;
    protected $goodDeliveryDetailRepository;
    protected $bomRepository;

    public function __construct(
        GoodReceiveDetailRepository $goodReceiveDetailRepository,
        GoodReceiveRepository $goodReceiveRepository,
        GoodDeliveryRepository $goodDeliveryRepository,
        GoodDeliveryDetailRepository $goodDeliveryDetailRepository,
        BomRepository $bomRepository
    )
    {
        $this->goodReceiveDetailRepository = $goodReceiveDetailRepository;
        $this->goodReceiveRepository = $goodReceiveRepository;
        $this->goodDeliveryRepository = $goodDeliveryRepository;
        $this->goodDeliveryDetailRepository = $goodDeliveryDetailRepository;
        $this->bomRepository = $bomRepository;
    }

    public function index()
    {
        return $this->goodReceiveDetailRepository->index();
    }

    public function find($id)
    {
        return $this->goodReceiveRepository->find($id);
    }

    public function create(Request $request)
    {
        $goodReceive = $this->goodReceiveRepository->create($request->all());

        for ($i = 0; $i < count($request->code); $i++) {
            $goodReceiveDetail = $this->goodReceiveDetailRepository->create($request, $i, $goodReceive->id);

            $bom_id = $request->bom_id[$i];
            if (isset($bom_id)) {
                $goodDelivery = $this->goodDeliveryRepository->firstOrCreateByReceive($goodReceive);

                $bom = $this->bomRepository->getBomDetails($bom_id);
                foreach ($bom->bomDetails as $bomDetail) {
                    $this->goodDeliveryDetailRepository->create($goodDelivery->id, $goodReceiveDetail, $bomDetail);
                }
            }
        }

        return $goodReceive;
    }

    public function show($id)
    {
        return $this->goodReceiveRepository->show($id);
    }

    public function update(Request $request, $id)
    {
        $this->goodReceiveRepository->update($request->all(), $id);
        $goodReceive = $this->goodReceiveRepository->find($id);

        $this->goodReceiveDetailRepository->update(['status' => 9], $id);

        for ($i = 0; $i< count($request->code); $i++) {
            $goodReceiveDetail = $this->goodReceiveDetailRepository->updateOrCreate($request, $i, $id);

            $this->goodDeliveryDetailRepository->update(['status' => 9], $goodReceiveDetail->id);
            $bom_id = $request->bom_id[$i];
            if (isset($bom_id)) {
                $goodDelivery = $this->goodDeliveryRepository->updateOrCreate($goodReceive);
                $bom = $this->bomRepository->getBomDetails($bom_id);

                foreach ($bom->bomDetails as $bomDetail) {
                    $this->goodDeliveryDetailRepository->updateOrCreate($goodDelivery->id, $goodReceiveDetail, $bomDetail);
                }
            }

            $this->goodDeliveryDetailRepository->deleteRemain('status', 9, $goodReceiveDetail->id);
        }

        $this->goodReceiveDetailRepository->deleteRemain('status', 9,  $id);

    }

    public function delete($id)
    {
        return $this->goodReceiveRepository->delete($id);
    }

    public function getNewNumber()
    {
        return $this->goodReceiveRepository->getNewNumber();
    }
}