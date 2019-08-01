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

    public function create(Request $request)
    {
        $goodReceive = $this->goodReceiveRepository->create($request->all());

        for ($i = 0; $i < count($request->code); $i++) {

            $goodReceiveDetail = $this->goodReceiveDetailRepository->create($request, $i, $goodReceive->id);

            $bom_id = $request->bom_id[$i];

            if (isset($bom_id)) {

                $goodDelivery = $this->goodDeliveryRepository->firstOrCreate($goodReceive);

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

    public function getNewNumber()
    {
        return $this->goodReceiveRepository->getNewNumber();
    }
}