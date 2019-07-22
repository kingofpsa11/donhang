<?php

namespace App\Http\Controllers;

use App\GoodDelivery;
use App\GoodReceiveDetail;
use App\GoodReceive;
use App\GoodDeliveryDetail;
use App\Role;
use App\User;
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
        return view('good-receives.index', compact('goodReceiveDetails'));
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
        return view('good-receives.create', compact('roles', 'newNumber'));
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
        return view('good-receives.show', compact('goodReceive'));
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
        return view('good-receives.edit', compact('goodReceive'));
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
//        $goodReceive->number = $request->goodReceive['number'];
//        $goodReceive->supplier_id = $request->goodReceive['supplier_id'];
//        $goodReceive->date = $request->goodReceive['date'];
//        $goodReceive->supplier_user = $request->goodReceive['supplier_user'];
//
//        $goodReceive->goodReceiveDetails()->update(['status' => 9]);
//
//        if ($goodReceive->save()) {
//            foreach ($request->goodReceiveDetails as $value) {
//                if (isset($value['id'])) {
//                    $goodReceiveDetail = GoodReceiveDetail::find($value['id']);
//                    $goodReceiveDetail->product_id = $value['product_id'];
//                    $goodReceiveDetail->quantity = $value['quantity'];
//                    if (isset($value['bom_id'])) {
//                        $goodReceiveDetail->bom_id = $value['bom_id'];
//                    }
//                    $goodReceiveDetail->store_id = $value['store_id'];
//                    $goodReceiveDetail->status = 10;
//                    $goodReceiveDetail->save();
//                } else {
//                    $goodReceiveDetail = new GoodReceiveDetail();
//                    $goodReceiveDetail->good_receive_id = $goodReceive->id;
//                    $goodReceiveDetail->product_id = $value['product_id'];
//                    $goodReceiveDetail->quantity = $value['quantity'];
//                    if (isset($value['bom_id'])) {
//                        $goodReceiveDetail->bom_id = $value['bom_id'];
//                    }
//                    $goodReceiveDetail->store_id = $value['store_id'];
//                    $goodReceiveDetail->status = 10;
//                    $goodReceiveDetail->save();
//                }
//            }
//
//            $goodReceive->goodReceiveDetails()->where('status',9)->delete();
//        }

        if ( $goodReceive->update($request->all()) ) {
            foreach ($request->code as $key => $value) {
                GoodReceiveDetail::updateOrCreate([
                    'id' => $request->good_receive_detail_id
                ],
                [
                    'good_receive_id' => $goodReceive->id,
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
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\GoodReceive  $goodReceive
     * @return \Illuminate\Http\Response
     */
    public function destroy(GoodReceive $goodReceive)
    {
        $goodReceive->goodDelivery->delete();
        $goodReceive->delete();
        flash('Đã xóa phiếu xuất ' . $goodReceive->number)->warning();
        return redirect()->route('good-receive.index');
    }

    public function getNewNumber()
    {
        $newNumber = GoodReceive::whereYear('date', date('Y',time()))
            ->max('number') + 1;
        if ($newNumber === null) {
            $newNumber = 1;
        }
        return $newNumber;
    }
}
