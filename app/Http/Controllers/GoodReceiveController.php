<?php

namespace App\Http\Controllers;

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
    protected $goodReceive;
    protected $goodReceiveDetail;

    public function __construct(GoodReceive $goodReceive)
    {
        $this->goodReceive = $goodReceive;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $goodReceiveDetails = GoodReceiveDetail::with('goodReceive')->get();
        return view('good-receive.index', compact('goodReceiveDetails'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::find([4,5]);
        $this->goodReceive->getNewNumber();
        $newNumber = $this->goodReceive->number;
        return view('good-receive.create', compact('roles', 'newNumber'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->goodReceive->fill($request->all());
        if ( $this->goodReceive->save() ) {
            foreach ($request->code as $key => $value) {
                $goodReceiveDetail = GoodReceiveDetail::create([
                    'good_receive_id' => $this->goodReceive->id,
                    'code' => $request->code[$key],
                    'product_id' => $request->product_id[$key],
                    'unit' => $request->unit[$key],
                    'bom_id' => $request->bom_id[$key],
                    'store_id' => $request->store_id[$key],
                    'quantity' => $request->quantity[$key]
                ]);

                if (isset($request->bom_id[$key])) {

                    $date = Carbon::createFromFormat(config('app.date_format'), $this->goodReceive->date, 'Asia/Bangkok')->format('Y-m-d');

                    $goodDelivery = GoodDelivery::where('good_receive_id', $this->goodReceive->id)
                        ->where('date', $date)
                        ->where('customer_id', $this->goodReceive->supplier_id)
                        ->first();

                    if (!$goodDelivery) {
                        $goodDelivery = GoodDelivery::create([
                            'good_receive_id' => $this->goodReceive->id,
                            'date' => $this->goodReceive->date,
                            'customer_id' => $this->goodReceive->supplier_id,
                            'number' => GoodDelivery::getNewNumber()
                        ]);
                    }

                    $bom = Bom::getBomDetails($request->bom_id[$key]);

                    foreach ($bom->bomDetails as $bomDetail) {
                        GoodDeliveryDetail::firstOrCreate([
                            'good_delivery_id' => $goodDelivery->id,
                            'good_receive_detail_id' => $goodReceiveDetail->id,
                            'product_id' => $bomDetail->product_id,
                            'actual_quantity' => $goodReceiveDetail->quantity * $bomDetail->quantity,
                            'store_id' => $goodReceiveDetail->store_id
                        ]);
                    }
                }
            }
        }

        return redirect()->route('good-receive.show', $this->goodReceive);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\GoodReceive  $goodReceive
     * @return \Illuminate\Http\Response
     */
    public function show(GoodReceive $goodReceive)
    {
        $goodReceive->load('goodReceiveDetails.product', 'goodReceiveDetails.bom', 'goodReceiveDetails.store', 'supplier');
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
        $goodReceive->load('goodReceiveDetails.product.bom', 'goodReceiveDetails.store');
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

            foreach ($request->code as $key => $value) {
                $goodReceiveDetail = GoodReceiveDetail::updateOrCreate([
                    'id' => $request->good_receive_detail_id[$key] ?? ''
                ],
                [
                    'good_receive_id' => $goodReceive->id,
                    'product_id' => $request->product_id[$key],
                    'unit' => $request->unit[$key],
                    'bom_id' => $request->bom_id[$key],
                    'store_id' => $request->store_id[$key],
                    'quantity' => $request->quantity[$key],
                    'status' => 10
                ]);

                $goodReceiveDetail->goodDeliveryDetails()->update(['status' => 9]);

                if (isset($request->bom_id[$key])) {

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

                    $bom = Bom::getBomDetails($request->bom_id[$key]);

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
        return GoodReceive::whereYear('date', date('Y',time()))
            ->max('number') + 1 ?? 1;
    }
}
