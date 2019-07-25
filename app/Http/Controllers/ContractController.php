<?php

namespace App\Http\Controllers;

use App\Contract;
use App\ContractDetail;
use App\Customer;
use App\ManufacturerOrder;
use App\ManufacturerOrderDetail;
use App\Supplier;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ContractController extends Controller
{
    protected $contract;

    public function __construct(Contract $contract)
    {
        $this->contract = $contract;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $contractDetails = ContractDetail::with(['contract.customer', 'price.product', 'manufacturerOrderDetail.manufacturerOrder'])
            ->orderBy('id', 'desc')
            ->get();
        return view('contract.index', compact('contractDetails'));
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
        $this->contract->fill($request->all());
        $this->contract->save();

        for ($i = 0; $i < count($request->code); $i++) {
            ContractDetail::create([
                'contract_id' => $this->contract->id,
                'price_id' => $request->price_id[$i],
                'quantity' => $request->quantity[$i],
                'deadline' => $request->deadline[$i],
                'selling_price' => $request->selling_price[$i],
                'supplier_id' => $request->supplier_id[$i],
            ]);
        }

        $user = User::find(11);
        $user->notify(new \App\Notifications\Contract($this->contract->id, $this->contract->status, $this->contract->number));

        return redirect()->route('contracts.show', $this->contract);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Contract  $contract
     * @return \Illuminate\Http\Response
     */
    public function show(Contract $contract)
    {
        $contract->load('contractDetails.price.product', 'contractDetails.supplier');
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
        $suppliers = Supplier::all();
        $contract->load('contractDetails.price.product', 'contractDetails.supplier');
        return view('contract.edit', compact('contract', 'suppliers'));
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
            foreach ($contract->contractDetails as $contractDetail) {
                $manufacturerOrder = ManufacturerOrder::where('contract_id', $contract->id)
                            ->where('supplier_id', $contractDetail->supplier_id)
                            ->first();

                if (!$manufacturerOrder) {
                    $manufacturerOrder = ManufacturerOrder::create([
                        'contract_id' => $contract->id,
                        'number' => ManufacturerOrder::getNewNumber(),
                        'supplier_id' => $contractDetail->supplier_id,
                        'date' => $contract->date,
                    ]);

                    ManufacturerOrderDetail::create([
                        'manufacturer_order_id' => $manufacturerOrder->id,
                        'contract_detail_id' => $contractDetail->id,
                    ]);

                } else {

                    $manufacturerOrderDetail = ManufacturerOrderDetail::where('manufacturer_order_id', $manufacturerOrder->id)
                        ->where('contract_detail_id', $contractDetail->id)
                        ->first();

                    if (!$manufacturerOrderDetail) {
                        ManufacturerOrderDetail::create([
                            'manufacturer_order_id' => $manufacturerOrder->id,
                            'contract_detail_id' => $contractDetail->id,
                        ]);

                    } else {
                        $manufacturerOrderDetail->update([
                            'manufacturer_order_id' => $manufacturerOrder->id,
                            'contract_detail_id' => $contractDetail->id,
                        ]);
                    }
                }
            }

            $users = User::role(4)->get();
            foreach ($users as $user) {
                $user->notify(new \App\Notifications\ManufacturerOrder($manufacturerOrder->id, $manufacturerOrder->number));
            }

        } else {
            $contract->update([
                $request->all(),
                'status' => 10,
            ]);

            $contract->contractDetails()->update(['status' => 9]);

            for ($i = 0; $i < count($request->code); $i++) {
                ContractDetail::updateOrCreate(
                    [
                        'id' => $request->contract_detail_id[$i]
                    ],
                    [
                        'contract_id' => $contract->id,
                        'price_id' => $request->price_id[$i],
                        'selling_price' => $request->selling_price[$i],
                        'deadline' => $request->deadline[$i],
                        'supplier_id' => $request->supplier_id[$i],
                        'note' => $request->note[$i],
                        'quantity' => $request->quantity[$i],
                        'status' => 10,
                    ]
                );
            }

            $contract->contractDetails()->where('status',9)->delete();

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
            ->join('manufacturer_orders', 'contract_details.id','=', 'manufacturer_orders.contract_id')
            ->join('manufacturer_order_details', 'manufacturer_orders.id', '=', 'manufacturer_order_id')
            ->select('products.name', 'manufacturer_orders.number', 'contract_details.id', DB::raw('products.id as product_id'))
            ->where('manufacturer_orders.number', $term)
            ->get();

        return response()->json($result);
    }

}
