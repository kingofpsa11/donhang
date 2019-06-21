<?php

namespace App\Http\Controllers;

use App\Contract;
use App\ContractDetail;
use App\Customer;
use App\ManufacturerNoteDetail;
use App\ManufacturerOrder;
use App\ManufacturerOrderDetail;
use App\Supplier;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ContractController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $contract_details = ContractDetail::with(['contract.customer', 'price.product'])->orderBy('id', 'desc')->take(1000)->get();
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
        $suppliers = Supplier::all();
        return view('contract.create', compact('customers', 'suppliers'));
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
//        $contract->number = $this->getLastContract($contract->customer_id);
        $contract->number = $request->contract['number'];
        $contract->date = $request->contract['date'];
        $contract->total_value = $request->contract['total_value'];

        if ($contract->save()) {
            $contract_details = [];
            foreach ($request->contract_details as $value) {
                $contract_detail = new ContractDetail();
                $contract_detail->price_id = $value['price_id'];
                $contract_detail->quantity = $value['quantity'];
                $contract_detail->deadline = $value['deadline'];
                $contract_detail->selling_price = $value['selling_price'];
                $contract_detail->supplier_id = $value['supplier_id'];
                array_push($contract_details, $contract_detail);
            }

            if($contract->contract_details()->saveMany($contract_details)) {
                $user = User::find(11);
                $user->notify(new \App\Notifications\Contract($contract->id, $contract->status, $contract->number));
                return redirect()->route('contract.show', $contract);
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
        return view('contract.show', compact('contract'));
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
        $suppliers = Supplier::all();
        $contract->load('contract_details');
        return view('contract.edit', compact('contract', 'customers', 'suppliers'));
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
        // Duyệt đơn hàng
        if (isset($request->approved)) {
            $contract->status = 5;
            $contract->save();

            $users = User::role(3)->get();
            foreach ($users as $user) {
                $user->notify(new \App\Notifications\Contract($contract->id, $contract->status, $contract->number));
            }

            $manufacturerOrder = null;
            foreach ($contract->contract_details as $contract_detail) {
                $existManufacturerOrder = ManufacturerOrder::where('contract_id', $contract->id)
                            ->where('supplier_id', $contract_detail->supplier_id)
                            ->count();

                if ($existManufacturerOrder === 0) {
                    $manufacturerOrder = new ManufacturerOrder();
                    $manufacturerOrder->contract_id = $contract->id;
                    $manufacturerOrder->number = ManufacturerOrder::where('supplier_id', $contract_detail->supplier_id)->max('number') + 1;
                    $manufacturerOrder->supplier_id = $contract_detail->supplier_id;
                    $manufacturerOrder->save();

                    $manufacturerOrderDetail = new ManufacturerOrderDetail();
                    $manufacturerOrderDetail->manufacturer_order_id = $manufacturerOrder->id;
                    $manufacturerOrderDetail->contract_detail_id = $contract_detail->id;
                    $manufacturerOrderDetail->save();

                } elseif ($existManufacturerOrder === 1) {
                    $manufacturerOrder = ManufacturerOrder::where('contract_id', $contract->id)
                        ->where('supplier_id', $contract_detail->supplier_id)
                        ->first();
                    $existManufacturerOrderDetail = ManufacturerOrderDetail::where('manufacturer_order_id', $manufacturerOrder->id)
                        ->where('contract_detail_id', $contract_detail->id)
                        ->count();

                    if ( $existManufacturerOrderDetail === 0 ) {
                        $manufacturerOrderDetail = new ManufacturerOrderDetail();
                        $manufacturerOrderDetail->manufacturer_order_id = $manufacturerOrder->id;
                        $manufacturerOrderDetail->contract_detail_id = $contract_detail->id;
                        $manufacturerOrderDetail->save();

                    } elseif ($existManufacturerOrderDetail === 1) {
                        $manufacturerOrderDetail = ManufacturerOrderDetail::where('manufacturer_order_id', $manufacturerOrder->id)
                            ->where('contract_detail_id', $contract_detail->id)
                            ->first();
                        $manufacturerOrderDetail->manufacturer_order_id = $manufacturerOrder->id;
                        $manufacturerOrderDetail->contract_detail_id = $contract_detail->id;
                        $manufacturerOrderDetail->save();
                    }
                }
            }

            $users = User::role(4)->get();
            foreach ($users as $user) {
                $user->notify(new \App\Notifications\ManufacturerOrder($manufacturerOrder->id, $manufacturerOrder->number));
            }
        } else {
            $contract->customer_id = $request->contract['customer_id'];
            $contract->number = $request->contract['number'];
            $contract->total_value = $request->contract['total_value'];
            $contract->date = $request->contract['date'];
            $contract->status = 10;

            $contract->contract_details()->update(['status' => 9]);

            if ($contract->save()) {
                foreach ($request->contract_details as $value) {
                    if (isset($value['id'])) {
                        $contract_detail = ContractDetail::find($value['id']);
                        $contract_detail->price_id = $value['price_id'];
                        $contract_detail->selling_price = $value['selling_price'];
                        $contract_detail->deadline = $value['deadline'];
                        $contract_detail->note = $value['note'];
                        $contract_detail->quantity = $value['quantity'];
                        $contract_detail->status = 10;
                        $contract_detail->save();
                    } else {
                        $contract_detail = new ContractDetail();
                        $contract_detail->price_id = $value['price_id'];
                        $contract_detail->selling_price = $value['selling_price'];
                        $contract_detail->deadline = $value['deadline'];
                        $contract_detail->note = $value['note'];
                        $contract_detail->quantity = $value['quantity'];
                        $contract_detail->save();
                    }
                }

                $contract->contract_details()->where('status',9)->delete();
            }

            $user = User::find(11);
            $user->notify(new \App\Notifications\Contract($contract->id, $contract->status, $contract->number));
        }

        return view('contract.show', compact('contract'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Contract  $contract
     * @return \Illuminate\Http\Response
     */
    public function destroy(Contract $contract)
    {
        $contract->delete();
        flash('Đã xóa đơn hàng ' . $contract->number)->success();
        return redirect()->route('contract.index');
    }

    public function  getLastContract($customer_id)
    {
        $lastContract = Contract::where('customer_id', $customer_id)->whereYear('date', '=', date('Y'))->orderBy('number', 'desc')->first();

        $newContract = $lastContract->number + 1;

        return $newContract;
    }

    public function checkNumber(Request $request)
    {
        $number = $request->number;
        $customer_id = $request->customer_id;
        $year = $request->year;

        $contract = Contract::where('customer_id', $customer_id)
            ->whereYear('date', $year)
            ->where('number', $number)
            ->count();

        return $contract;
    }

    public function shows(Request $request)
    {
        $term = $request->search;

        $result = DB::table('contracts')
            ->join('contract_details', 'contracts.id', '=', 'contract_details.contract_id')
            ->join('prices', 'prices.id', '=', 'contract_details.price_id')
            ->join('products', 'products.id', '=', 'prices.product_id')
            ->leftJoin('output_order_details', 'contract_details.id', '=', 'output_order_details.contract_detail_id')
            ->select('products.name', 'products.code', 'contract_details.id', 'contracts.number', DB::raw('(`contract_details`.`quantity` - IFNULL(SUM(`output_order_details`.`quantity`),0)) AS `remain_quantity`'))
            ->where('contracts.number', 'LIKE', '%' . $term . '%')
            ->where('customer_id', '=', $request->customer_id)
            ->groupBy('contract_details.id', 'products.name', 'products.code', 'contracts.number', 'contract_details.quantity')
            ->having('remain_quantity', '>', 0)
            ->take(10)
            ->get();

        return response()->json($result);
    }

    public function getManufacturerOrder(Request $request)
    {
        $term = $request->term;

        $result = DB::table('contracts')
            ->join('contract_details', 'contracts.id', '=', 'contract_details.contract_id')
            ->join('prices', 'prices.id', '=', 'contract_details.price_id')
            ->join('products', 'products.id', '=', 'prices.product_id')
            ->join('manufacturer_orders', 'contract_details.manufacturer_order_id','=', 'manufacturer_orders.id')
            ->select('products.name', 'manufacturer_orders.number', 'contract_details.id', DB::raw('products.id as product_id'))
            ->where('manufacturer_orders.number', $term)
            ->get();

        return response()->json($result);
    }

}
