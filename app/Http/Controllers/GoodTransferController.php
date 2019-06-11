<?php

namespace App\Http\Controllers;

use App\GoodTransfer;
use App\GoodTransferDetail;
use Illuminate\Http\Request;
use App\User;
use App\Bom;

class GoodTransferController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $goodTransfers = GoodTransfer::all();
        return view('good-transfer.index', compact('goodTransfers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('good-transfer.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $goodTransfer = new GoodTransfer();
        $goodTransfer->receive_number = $request->goodTransfer['number'];
        $goodTransfer->date = $request->goodTransfer['date'];
        $goodTransfer->delivery_store = $request->goodTransfer['delivery_store'];
        $goodTransfer->receive_store = $request->goodTransfer['receive_store'];
        $goodTransfer->user_id = auth()->user()->id;

        if ($goodTransfer->save()) {
            $goodTransferDetails = [];
            foreach($request->goodTransferDetails as $value) {
                $goodTransferDetail = new GoodTransferDetail();
                $goodTransferDetail->good_transfer_id = $goodTransfer->id;
                $goodTransferDetail->product_id = $value['product_id'];
                $goodTransferDetail->bom_id = $value['bom_id'];
                $goodTransferDetail->receive_quantity = $value['quantity'];
                array_push($goodTransferDetails, $goodTransferDetail);
            }

            if($goodTransfer->goodTransferDetails()->saveMany($goodTransferDetails)) {
                return redirect()->route('good-transfer.show', $goodTransfer);
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\GoodTransfer  $goodTransfer
     * @return \Illuminate\Http\Response
     */
    public function show(GoodTransfer $goodTransfer)
    {
        return view('good-transfer.show', compact('goodTransfer'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\GoodTransfer  $goodTransfer
     * @return \Illuminate\Http\Response
     */
    public function edit(GoodTransfer $goodTransfer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\GoodTransfer  $goodTransfer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, GoodTransfer $goodTransfer)
    {
        if (isset($request->approved)) {
            $goodTransfer->status = 5;
            $goodTransfer->save();
            $goodTransferId = $goodTransfer->id;

            $bomGoodTransfer = new GoodTransfer();
            $bomGoodTransfer->delivery_number = $goodTransfer->receive_number;
            $bomGoodTransfer->date = time();
            $bomGoodTransfer->delivery_store = $goodTransfer->delivery_store;
            $bomGoodTransfer->receive_store = $goodTransfer->receive_store;
            $bomGoodTransfer->status = 5;
            $bomGoodTransfer->user_id = auth()->user()->id;

            if ($bomGoodTransfer->save()) {
                $bomGoodTransferDetails = [];
                foreach ($goodTransfer->goodTransferDetails as $goodTransferDetail) {
                    $bom = Bom::find($goodTransferDetail->bom_id);
                    foreach ($bom->bomDetails as $bomDetail) {
                        $bomGoodTransferDetail = new GoodTransferDetail();
                        $bomGoodTransferDetail->good_transfer_id = $bomGoodTransfer->id;
                        $bomGoodTransferDetail->product_id = $bomDetail->product_id;
                        $bomGoodTransferDetail->delivery_quantity = $goodTransferDetail->receive_quantity * $bomDetail->quantity;
                        array_push($bomGoodTransferDetails, $bomGoodTransferDetail);
                    }
                }

                $bomGoodTransfer->goodTransferDetails()->saveMany($bomGoodTransferDetails);
            }

            $users = User::role('Nhân viên')->get();
            foreach ($users as $user) {
                $user->notify(new \App\Notifications\GoodTransfer($goodTransferId));
            }

            return redirect()->route('good-transfer.index');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\GoodTransfer  $goodTransfer
     * @return \Illuminate\Http\Response
     */
    public function destroy(GoodTransfer $goodTransfer)
    {
        //
    }

    public function showInventory()
    {
        $inventory = GoodTransferDetail::groupBy('product_id')
            ->selectRaw('(sum(receive_quantity) - sum(delivery_quantity)) as quantity, product_id')
            ->get();
        return view('good-transfer.showInventory',compact('inventory'));
    }
}