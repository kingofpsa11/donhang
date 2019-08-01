<?php

namespace App\Http\Controllers;

use App\Services\GoodReceiveService;
use App\GoodDelivery;
use App\GoodReceiveDetail;
use App\GoodReceive;
use App\GoodDeliveryDetail;
use App\Role;
use App\Bom;
use Carbon\Carbon;
use Illuminate\Http\Request;

class GoodReceiveController extends Controller
{
    protected $goodReceiveService;

    public function __construct(GoodReceiveService $goodReceiveService)
    {
        $this->goodReceiveService = $goodReceiveService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $goodReceiveDetails = $this->goodReceiveService->index();
        return view('good-receive.index', compact('goodReceiveDetails'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $newNumber = $this->goodReceiveService->getNewNumber();
        $roles = Role::find([4,5]);
        return view('good-receive.create', compact('newNumber', 'roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $goodReceive = $this->goodReceiveService->create($request);

        return redirect()->route('good-receive.show', $goodReceive);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\GoodReceive  $goodReceive
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $goodReceive = $this->goodReceiveService->show($id);
        var_dump($goodReceive->supplier()->name);
        die;
        return view('good-receive.show', compact('goodReceive'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\GoodReceive  $goodReceive
     * @return \Illuminate\Http\Response
     */
    public function edit(GoodReceive $goodReceive)
    {
        $goodReceive->load('goodReceiveDetails.product.boms', 'goodReceiveDetails.store');
        return view('good-receive.edit', compact('goodReceive'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\GoodReceive  $goodReceive
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, GoodReceive $goodReceive)
    {
        if ($goodReceive->update([$request->all()])) {
            $goodReceive->goodReceiveDetails()->update(['status' => 9]);

            for ($i = 0; $request->code; $i++) {
                $goodReceiveDetail = GoodReceiveDetail::updateOrCreate([
                    'id' => $request->good_receive_detail_id[$i] ?? ''
                ],
                [
                    'good_receive_id' => $goodReceive->id,
                    'product_id' => $request->product_id[$i],
                    'unit' => $request->unit[$i],
                    'bom_id' => $request->bom_id[$i],
                    'store_id' => $request->store_id[$i],
                    'quantity' => $request->quantity[$i],
                    'status' => 10
                ]);

                $goodReceiveDetail->goodDeliveryDetails()->update(['status' => 9]);

                if (isset($request->bom_id[$i])) {

                    $date = Carbon::createFromFormat(config('app.date_format'), $goodReceive->date, 'Asia/Bangkok')->format('Y-m-d');

                    $goodDelivery = GoodDelivery::where('good_receive_id', $goodReceive->id)
                        ->where('date', $date)
                        ->where('customer_id', $goodReceive->supplier_id)
                        ->first();

                    if (!$goodDelivery) {
                        $goodDelivery = GoodDelivery::create([
                            'good_receive_id' => $goodReceive->id,
                            'date' => $goodReceive->date,
                            'customer_id' => $goodReceive->supplier_id,
                            'number' => GoodDelivery::getNewNumber()
                        ]);
                    }

                    $bom = Bom::getBomDetails($request->bom_id[$i]);

                    foreach ($bom->bomDetails as $bomDetail) {
                        GoodDeliveryDetail::updateOrCreate([
                                'good_delivery_id' => $goodDelivery->id,
                                'good_receive_detail_id' => $goodReceiveDetail->id,
                                'product_id' => $bomDetail->product_id,
                                'store_id' => $goodReceiveDetail->store_id
                            ],
                            [
                                'actual_quantity' => $goodReceiveDetail->quantity * $bomDetail->quantity,
                                'status' => 10
                            ]
                        );
                    }
                }

                $goodReceiveDetail->goodDeliveryDetails()->where('status', 9)->delete();
            }

            $goodReceive->goodReceiveDetails()->where('status',9)->delete();
        }
        return view('good-receive.show', compact('goodReceive'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\GoodReceive  $goodReceive
     * @return \Illuminate\Http\Response
     */
    public function destroy(GoodReceive $goodReceive)
    {
        $goodReceive->delete();
        flash('Đã xóa phiếu nhập ' . $goodReceive->number)->warning();
        return redirect()->route('good-receive.index');
    }

    public function getNewNumber()
    {

    }
}
