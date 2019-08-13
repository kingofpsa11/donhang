<?php

namespace App\Http\Controllers;

use App\Customer;
use App\OutputOrder;
use App\OutputOrderDetail;
use App\GoodDelivery;
use App\GoodDeliveryDetail;
use App\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class OutputOrderController extends Controller
{
    protected $outputOrder;

    public function __construct(OutputOrder $outputOrder)
    {
        $this->outputOrder = $outputOrder;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $outputOrderDetails = OutputOrderDetail::with('outputOrder.customer', 'contractDetail.price.product')->get();
        return view('output_order.index',compact('outputOrderDetails'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('output_order.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->outputOrder->fill($request->all())->save();

        for ($i = 0; $i < count($request->code); $i++) {
            OutputOrderDetail::create([
                'output_order_id' => $this->outputOrder->id,
                'contract_detail_id' => $request->contract_detail_id[$i],
                'quantity' => $request->quantity[$i],
            ]);
        }

        $users = User::role(7)->get();
        foreach ($users as $user) {
            $user->notify(new \App\Notifications\OutputOrderApproved($this->outputOrder));
        }

        return redirect()->route('output-orders.show', $this->outputOrder);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\OutputOrder  $outputOrder
     * @return \Illuminate\Http\Response
     */
    public function show(OutputOrder $outputOrder)
    {
        $outputOrder->load('outputOrderDetails.contractDetail.price.product');
        return view('output_order.show', compact('outputOrder'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\OutputOrder  $outputOrder
     * @return \Illuminate\Http\Response
     */
    public function edit(OutputOrder $outputOrder)
    {
        $outputOrder->load('outputOrderDetails.contractDetail.contract.customer','outputOrderDetails.contractDetail.price.product');
        return view('output_order.edit',compact('outputOrder'));
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
        // Duyệt lệnh xuất hàng
        if (isset($request->approved)) {
            $outputOrder->status = 5;
            $outputOrder->save();

            $goodDelivery = $outputOrder->delivery()->updateOrCreate(
                [
                    'deliverable_id' => $outputOrder->id,
                ],
                [
                    'number' => GoodDelivery::getNewNumber(),
                    'customer_id' => $outputOrder->customer_id,
                    'customer_user' => $outputOrder->customer_user,
                    'date' => date('d/m/Y')
                ]
            );

            foreach ($outputOrder->outputOrderDetails as $outputOrderDetail) {
                $outputOrderDetail->delivery()->updateOrCreate(
                    [
                        'good_delivery_id' => $goodDelivery->id,
                        'product_id' => $outputOrderDetail->contractDetail->price->product->id,
                        'quantity' => $outputOrderDetail->quantity,
                    ]
                );
            }

            $users = User::role(5)->get();

            foreach ($users as $user) {
                $user->notify(new \App\Notifications\OutputOrder($outputOrder->number, $goodDelivery->id));
            }

            return redirect()->route('output-orders.index');

        } else {
            $outputOrder->update($request->all());
            if ($outputOrder->isDirty()) {
                $outputOrder->update(['status' => 10]);
            }
            $outputOrder->outputOrderDetails()->update(['status' => 9]);

            for ($i = 0; $i < count($request->code); $i++) {
                OutputOrderDetail::updateOrCreate(
                    [
                        'id' => $request->output_order_detail_id[$i],
                    ],
                    [
                        'output_order_id' => $outputOrder->id,
                        'contract_detail_id' => $request->contract_detail_id[$i],
                        'quantity' => $request->quantity[$i],
                        'status' => 10
                    ]
                );
            }

            $outputOrder->outputOrderDetails()->where('status', 9)->delete();

            return redirect()->route('output-orders.show', $outputOrder);
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
        return redirect()->route('output-orders.index');
    }

    public function getUndoneOutputOrders()
    {
        $undoneOutputOrders = OutputOrder::where('status', 10)->get();

        return view('output_order.undone', compact('undoneOutputOrders'));
    }

    public function existNumber(Request $request)
    {
        $number = $request->number;
        $customer_id = $request->customer_id;
        $year = $request->year;

        $outputOrder = OutputOrder::where('customer_id', $customer_id)
            ->whereYear('date', $year)
            ->where('number', $number)
            ->count();

        return $outputOrder;
    }
}
