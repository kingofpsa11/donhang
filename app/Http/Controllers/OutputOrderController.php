<?php

namespace App\Http\Controllers;

use App\Customer;
use App\OutputOrder;
use App\OutputOrderDetail;
use Illuminate\Http\Request;
use Carbon\Carbon;

class OutputOrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $outputOrderDetails = OutputOrderDetail::with(['outputOrder.customer', 'contractDetail.price.product'])->take(1000)->get();
        return view('output_order.index')->with('outputOrderDetails', $outputOrderDetails);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $customers = Customer::all();
        return view('output_order.create')->with(['customers' => $customers]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $numberIsExist = OutputOrder::where('number', $request->outputOrder['number'])
            ->where('customer_id', $request->outputOrder['customer_id'])
            ->whereYear('date', date('Y', (int)$request->outputOrder['date']))
            ->count();

        if ($numberIsExist > 0) {
            flash('Số lệnh xuất hàng đã tồn tại')->error();
            return redirect()->back();
        }

        $outputOrder = new OutputOrder();
        $outputOrder->customer_id = $request->outputOrder['customer_id'];
        $outputOrder->number = $request->outputOrder['number'];
        $outputOrder->date = $request->outputOrder['date'];

        if ($outputOrder->save()) {
            $outputOrderDetails = [];
            foreach ($request->outputOrderDetails as $value) {
                $outputOrderDetail = new OutputOrderDetail();
                $outputOrderDetail->contract_detail_id = $value['contract_detail_id'];
                $outputOrderDetail->quantity = $value['quantity'];
                array_push($outputOrderDetails, $outputOrderDetail);
            }

            if($outputOrder->outputOrderDetails()->saveMany($outputOrderDetails)) {
                return redirect()->route('output-order.show', [$outputOrder]);
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\OutputOrder  $outputOrder
     * @return \Illuminate\Http\Response
     */
    public function show(OutputOrder $outputOrder)
    {
        $outputOrder->load('outputOrderDetails');
        return view('output_order.show')->with('outputOrder', $outputOrder);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\OutputOrder  $outputOrder
     * @return \Illuminate\Http\Response
     */
    public function edit(OutputOrder $outputOrder)
    {
        $customers = Customer::all();
        $outputOrder->load('outputOrderDetails');
        return view('output_order.edit')->with(['outputOrder' => $outputOrder, 'customers' => $customers]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\OutputOrder  $outputOrder
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, OutputOrder $outputOrder)
    {
        $outputOrder->customer_id = $request->outputOrder['customer_id'];
        $outputOrder->number = $request->outputOrder['customer_id'];;
        $outputOrder->date = $request->outputOrder['date'];

        if ($outputOrder->save()) {
            $outputOrder->outputOrderDetails()->delete();

            $outputOrderDetails = [];
            foreach ($request->outputOrderDetails as $value) {
                $outputOrderDetail = new OutputOrderDetail();
                $outputOrderDetail->price_id = $value['price_id'];
                $outputOrderDetail->selling_price = $value['selling_price'];
                $outputOrderDetail->deadline = $value['deadline'];
                $outputOrderDetail->note = $value['note'];
                $outputOrderDetail->quantity = $value['quantity'];
                $outputOrderDetail->status = 10;
                array_push($outputOrderDetails, $outputOrderDetail);
            }

            if($outputOrder->outputOrderDetails()->saveMany($outputOrderDetails)) {
                return redirect()->route('output-order.show', [$outputOrder]);
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\OutputOrder  $outputOrder
     * @return \Illuminate\Http\Response
     */
    public function destroy(OutputOrder $outputOrder)
    {
        $outputOrder->delete();
        flash('Đã xoá lệnh xuất hàng số ' . $outputOrder->number)->success();
        return redirect()->route('output-order.index');
    }

    public function getUndoneOutputOrders()
    {
        $undoneOutputOrders = OutputOrder::where('status', 10)->get();

        return view('output_order.undone', compact('undoneOutputOrders'));
    }
}
