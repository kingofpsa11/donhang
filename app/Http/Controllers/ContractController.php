<?php

namespace App\Http\Controllers;

use App\Contract;
use App\ContractDetail;
use App\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ContractController extends Controller
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

        $contract_details = ContractDetail::with(['contract.customer', 'price.product'])->orderBy('id','desc')->take(1000)->get();
        return view('contract.index')->with('contract_details', $contract_details);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $customers = Customer::all();
        return view('contract.create')->with(['customers' => $customers]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $contract = new Contract();
        $contract->customer_id = $request->contract['customer_id'];
        $contract->number = $this->getLastContract($contract->customer_id);
        $contract->date = $request->contract['date'];
        $contract->total_value = $request->contract['total_value'];

        if ($contract->save()) {
            $contract_details = [];
            foreach ($request->contract_detail as $value) {
                $contract_detail = new ContractDetail();
                $contract_detail->price_id = $value['price_id'];
                $contract_detail->quantity = $value['quantity'];
                $contract_detail->deadline = $value['deadline'];
                $contract_detail->selling_price = $value['selling_price'];
                array_push($contract_details, $contract_detail);
            }

            if($contract->contract_details()->saveMany($contract_details)) {
                return redirect()->route('contract.show', [$contract]);
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Contract  $contract
     * @return \Illuminate\Http\Response
     */
    public function show(Contract $contract)
    {
        $contract->load('contract_details');
        return view('contract.show')->with('contract', $contract);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Contract  $contract
     * @return \Illuminate\Http\Response
     */
    public function edit(Contract $contract)
    {
        $customers = Customer::all();
        $contract->load('contract_details');
        return view('contract.edit')->with(['contract' => $contract, 'customers' => $customers]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Contract  $contract
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Contract $contract)
    {
        $contract->number = $request->contract['number'];
        $contract->total_value = $request->contract['total_value'];
        $contract->date = $request->contract['date'];

        if ($contract->save()) {
            $contract->contract_details()->delete();

            $contract_details = [];
            foreach ($request->contract_detail as $value) {
                $contract_detail = new ContractDetail();
                $contract_detail->price_id = $value['price_id'];
                $contract_detail->selling_price = $value['selling_price'];
                $contract_detail->deadline = $value['deadline'];
                $contract_detail->note = $value['note'];
                $contract_detail->quantity = $value['quantity'];
                $contract_detail->status = 10;
                array_push($contract_details, $contract_detail);
            }

            if($contract->contract_details()->saveMany($contract_details)) {
                return redirect()->route('contract.show', ['contract' => $contract]);
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Contract  $contract
     * @return \Illuminate\Http\Response
     */
    public function destroy(Contract $contract)
    {
        //
    }

    public function  getLastContract($customer_id)
    {
        $lastContract = Contract::where('customer_id', $customer_id)->whereYear('date', '=', date('Y'))->orderBy('number', 'desc')->first();

        $newContract = $lastContract->number + 1;

        return $newContract;
    }

    public function shows(Request $request)
    {
        $term = $request->search;

        $result = DB::table('contracts')
            ->join('contract_details', 'contracts.id', '=', 'contract_details.contract_id')
            ->join('prices', 'prices.id', '=', 'contract_details.price_id')
            ->join('products', 'products.id', '=', 'prices.product_id')
            ->leftJoin('output_order_details', 'contract_details.id', '=', 'output_order_details.contract_detail_id')
            ->select('products.name', 'products.code', 'contract_details.id', 'contracts.number', DB::raw('(`contract_details`.`quantity` - IFNULL(`output_order_details`.`quantity`, 0)) as quantity'))
            ->where('contracts.number', 'LIKE', '%' . $term . '%')
            ->where('customer_id', '=', $request->customer_id)
            ->take(10)
            ->get();

        return response()->json($result);
    }
}
