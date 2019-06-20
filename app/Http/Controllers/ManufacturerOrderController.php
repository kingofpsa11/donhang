<?php

namespace App\Http\Controllers;

use App\Contract;
use App\ContractDetail;
use App\ManufacturerOrder;
use App\ManufacturerOrderDetail;
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
        $manufacturerOrderDetails = ManufacturerOrderDetail::all();
        return view('manufacturer_order.index', compact('manufacturerOrderDetails'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $suppliers = Supplier::all();
        return view('manufacturer_order.create', compact('suppliers'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\ManufacturerOrder  $manufacturerOrder
     * @return \Illuminate\Http\Response
     */
    public function show(ManufacturerOrder $manufacturerOrder)
    {
        return response()->view('manufacturer_order.show', compact('manufacturerOrder'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ManufacturerOrder  $manufacturerOrder
     * @return \Illuminate\Http\Response
     */
    public function edit(Contract $contract)
    {
        $suppliers = Supplier::all();
        return view('manufacturer_order.edit')->with(['contract' => $contract, 'suppliers' => $suppliers]);
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

    public function showManufacturer()
    {
        $contract_details = ContractDetail::whereNotNull('manufacturer_order_id')
            ->where('status', '!=', 0)
            ->orderBy('id', 'desc')
            ->take(1000)
            ->get();
        return view('manufacturer_order.index')->with('contract_details', $contract_details);
    }
}
