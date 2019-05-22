<?php

namespace App\Http\Controllers;

use App\GoodTransfer;
use App\GoodTransferDetail;
use Illuminate\Http\Request;

class GoodTransferController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
//                $goodTransferDetail->bom_id = $value['bom_id'];
                $goodTransferDetail->quantity = $value['quantity'];
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
        //
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
}
