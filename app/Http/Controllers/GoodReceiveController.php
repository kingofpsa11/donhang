<?php

namespace App\Http\Controllers;

use App\GoodDelivery;
use App\GoodReceiveDetail;
use App\GoodReceive;
use App\GoodDeliveryDetail;
use App\Role;
use App\User;
use App\Bom;
use Illuminate\Http\Request;

class GoodReceiveController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $goodReceiveDetails = GoodReceiveDetail::all();
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
        return view('good-receives.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $goodReceive = new GoodReceive();
        $goodReceive->number = $request->goodReceive['number'];
        $goodReceive->supplier_id = $request->goodReceive['supplier_id'];
        $goodReceive->date = $request->goodReceive['date'];
        $goodReceive->supplier_user = $request->goodReceive['supplier_user'];

        if ($goodReceive->save()) {
            $goodReceiveDetails = [];
            foreach ($request->goodReceiveDetails as $value) {
                $goodReceiveDetail = new GoodReceiveDetail();
                $goodReceiveDetail->good_receive_id = $goodReceive->id;
                $goodReceiveDetail->product_id = $value['product_id'];
                $goodReceiveDetail->quantity = $value['quantity'];
                $goodReceiveDetail->bom_id = $value['bom_id'];
                $goodReceiveDetail->store_id = $value['store_id'];
                array_push($goodReceiveDetails, $goodReceiveDetail);
            }

            if($goodReceive->goodReceiveDetails()->saveMany($goodReceiveDetails)) {

                $user = User::find(9);
                $user->notify(new \App\Notifications\GoodReceive($goodReceive->id, $goodReceive->number));

                return redirect()->route('good-receive.show', $goodReceive);
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\GoodReceive  $goodReceive
     * @return \Illuminate\Http\Response
     */
    public function show(GoodReceive $goodReceive)
    {
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
        if (isset($request->approved)) {
            $goodReceive->status = 5;
            $goodReceive->save();
            $goodReceiveId = $goodReceive->id;

            if (isset($goodReceive->goodReceiveDetails->first()->bom_id)) {
                $bomGoodDelivery = new GoodDelivery();
                $bomGoodDelivery->good_receive_id = $goodReceive->id;
                $bomGoodDelivery->number = $goodReceive->number;
                $bomGoodDelivery->customer_id = $goodReceive->supplier_id;
                $bomGoodDelivery->date = time();
                $bomGoodDelivery->status = 5;
//                $bomGoodDelivery->user_id = auth()->user()->id;

                if ($bomGoodDelivery->save()) {
                    $bomGoodDeliveryDetails = [];
                    foreach ($goodReceive->goodReceiveDetails as $goodReceiveDetail) {
                        $bom = Bom::find($goodReceiveDetail->bom_id);
                        foreach ($bom->bomDetails as $bomDetail) {
                            $bomGoodDeliveryDetail = new GoodDeliveryDetail();
                            $bomGoodDeliveryDetail->good_delivery_id = $bomGoodDelivery->id;
                            $bomGoodDeliveryDetail->product_id = $bomDetail->product_id;
                            $bomGoodDeliveryDetail->actual_quantity = $goodReceiveDetail->quantity * $bomDetail->quantity;
                            $bomGoodDeliveryDetail->store_id = $goodReceiveDetail->store_id;
                            array_push($bomGoodDeliveryDetails, $bomGoodDeliveryDetail);
                        }
                    }

                    $bomGoodDelivery->goodDeliveryDetails()->saveMany($bomGoodDeliveryDetails);
                }
            }

            $users = User::role(4)->get();
            foreach ($users as $user) {
                $user->notify(new \App\Notifications\GoodReceive($goodReceiveId));
            }

            return redirect()->route('good-receive.index');
        }

        if($request->goodReceiveDetails[0]['actual_quantity'] != 0) {
            foreach ($request->goodReceiveDetails as $value) {
                $goodReceiveDetail = GoodReceiveDetail::find($value['id']);
                $goodReceiveDetail->actual_quantity = $value['actual_quantity'];
                $goodReceiveDetail->save();
            }
        } else {
            $goodReceive->number = $request->goodReceive['number'];
            $goodReceive->supplier_id = $request->goodReceive['supplier_id'];
            $goodReceive->date = $request->goodReceive['date'];
            $goodReceive->supplier_user = $request->goodReceive['supplier_user'];

            $goodReceive->goodReceiveDetails()->update(['status' => 9]);

            if ($goodReceive->save()) {
                foreach ($request->goodReceiveDetails as $value) {
                    if (isset($value['id'])) {
                        $goodReceiveDetail = GoodReceiveDetail::find($value['id']);
                        $goodReceiveDetail->product_id = $value['product_id'];
                        $goodReceiveDetail->quantity = $value['quantity'];
                        $goodReceiveDetail->bom_id = $value['bom_id'];
                        $goodReceiveDetail->store_id = $value['store_id'];
                        $goodReceiveDetail->status = 10;
                        $goodReceiveDetail->save();
                    } else {
                        $goodReceiveDetail = new GoodReceiveDetail();
                        $goodReceiveDetail->good_receive_id = $goodReceive->id;
                        $goodReceiveDetail->product_id = $value['product_id'];
                        $goodReceiveDetail->quantity = $value['quantity'];
                        $goodReceiveDetail->bom_id = $value['bom_id'];
                        $goodReceiveDetail->store_id = $value['store_id'];
                        $goodReceiveDetail->status = 10;
                        $goodReceiveDetail->save();
                    }
                }

                $goodReceive->goodReceiveDetails()->where('status',9)->delete();
            }
        }

        return view('good-receives.show', compact('goodReceive'));
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
        flash('Đã xóa phiếu xuất ' . $goodReceive->number)->warning();
        return redirect()->route('good-receive.index');
    }
}
