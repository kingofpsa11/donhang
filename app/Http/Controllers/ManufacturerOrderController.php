<?php

namespace App\Http\Controllers;

use App\Contract;
use App\ContractDetail;
use App\ManufacturerOrder;
use App\Supplier;
use App\User;
use Illuminate\Http\Request;

class ManufacturerOrderController extends Controller
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
    public function create(Contract $contract)
    {
        $suppliers = Supplier::all();
        return view('manufacturer_order.create')->with(['contract' => $contract, 'suppliers' => $suppliers]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Contract $contract)
    {
        $newNumber = 0;
        $supplier_id = null;
        $manufacturer_id = null;

        foreach ($request->contract_detail as $value) {
            if (($newNumber == 0 && $supplier_id == null) || $supplier_id != $value['supplier_id']) {
                $manufacturerOrder = new ManufacturerOrder();
                $manufacturerOrder->supplier_id = $value['supplier_id'];
                $newNumber = $this->getNewNumber($manufacturerOrder['supplier_id']);
                $supplier_id = $value['supplier_id'];
                $manufacturerOrder->number = $newNumber;
                $manufacturerOrder->save();
                $manufacturer_id = $manufacturerOrder->id;
            }
            $contractDetail = ContractDetail::find($value['id']);
            $contractDetail->manufacturer_order_id = $manufacturer_id;
            $contractDetail->save();
        }

        $users = User::role('Nhà máy')->get();
        foreach ($users as $user) {
            $user->notify(new \App\Notifications\ManufacturerOrder($manufacturer_id));
        }

        return redirect()->route('manufacturer-order.show', $contract);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\ManufacturerOrder  $manufacturerOrder
     * @return \Illuminate\Http\Response
     */
    public function show(Contract $contract)
    {
        return response()->view('manufacturer_order.show',['contract' => $contract]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ManufacturerOrder  $manufacturerOrder
     * @return \Illuminate\Http\Response
     */
    public function edit(ManufacturerOrder $manufacturerOrder)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ManufacturerOrder  $manufacturerOrder
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ManufacturerOrder $manufacturerOrder)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ManufacturerOrder  $manufacturerOrder
     * @return \Illuminate\Http\Response
     */
    public function destroy(ManufacturerOrder $manufacturerOrder)
    {
        //
    }

    public function getNewNumber($supplier_id)
    {
        $newNumber =  ManufacturerOrder::where('supplier_id', $supplier_id)->whereYear('created_at', '>=', date('Y'))->orderBy('number', 'desc')->first();
        if (!isset($newNumber)) {
            return 1;
        }
        return ((int)$newNumber->number + 1);
    }
}
