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
        return response()->view('contract.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $customers = Customer::all();
        $suppliers = Supplier::whereIn('id', [74, 584, 994])->get();
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
        $suppliers = Supplier::whereIn('id', [74, 584, 994])->get();
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
                        'number' => ManufacturerOrder::getNewNumber($contractDetail->supplier_id),
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
            $contract->update($request->all());
            if ($contract->isDirty()) {
                $contract->update(['status' => 10]);
            }
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

            if ($contract->isDirty()) {
                $user = User::find(11);
                $user->notify(new \App\Notifications\Contract($contract->id, $contract->status, $contract->number));
            }
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

    public function existNumber(Request $request)
    {
        $number = $request->number;
        $customer_id = $request->customer_id;
        $year = $request->year;

        $count = Contract::where('customer_id', $customer_id)
            ->whereYear('date', $year)
            ->where('number', $number)
            ->count();

        return $count;
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

    public function allContracts(Request $request)
    {
//        return array_keys(array_filter(array_column(array_column($request->columns, 'search'), 'value')));

        $columns = array(
            0 =>'customer',
            1 =>'number',
            2=> 'product',
            3=> 'quantity',
            4=> 'selling_price',
            5=> 'date',
            6=> 'deadline',
            7=> 'order',
            8=> 'status',
            9=> 'view',
            10=> 'edit',
        );

        $totalData = ContractDetail::count();

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        $manufacturerOrderDetails = DB::table('manufacturer_order_details')
            ->join('manufacturer_orders', 'manufacturer_orders.id','=','manufacturer_order_details.manufacturer_order_id')
            ->select('manufacturer_order_details.id', 'manufacturer_orders.number', 'manufacturer_order_details.contract_detail_id');

        $contracts = DB::table('contracts')
            ->leftJoin('customers', 'customers.id', '=', 'contracts.customer_id')
            ->select('contracts.id', 'contracts.number', 'contracts.date', 'customers.name');

        $prices = DB::table('prices')
            ->leftJoin('products', 'products.id', '=', 'prices.product_id')
            ->select('products.name', 'prices.id');

        $query = DB::table('contract_details')
            ->joinSub($contracts, 'contracts', function ($join) {
                $join->on('contract_details.contract_id', '=', 'contracts.id');
            })
            ->leftJoinSub($manufacturerOrderDetails, 'manufacturerOrder', function ($join) {
                $join->on('contract_details.id', '=', 'manufacturerOrder.contract_detail_id');
            })
            ->leftJoinSub($prices, 'prices', function ($join) {
                $join->on('contract_details.price_id', '=', 'prices.id');
            })
            ->select(
                'contracts.id',
                'contracts.name AS customer',
                'contracts.number',
                'prices.name AS product',
                'contract_details.quantity',
                'contract_details.selling_price',
                'contracts.date',
                'contract_details.deadline',
                'manufacturerOrder.number AS manufacturer',
                'contract_details.status'
            );

        if (empty($request->input('search.value')) && !array_filter(array_column(array_column($request->columns, 'search'), 'value'))) {

        } elseif (!empty($request->input('search.value'))) {

                $search = $request->input('search.value');

                $query = $query->where('contracts.name', 'LIKE', "%{$search}%")
                    ->orWhere('contracts.number', 'LIKE', "%{$search}%")
                    ->orWhere('prices.name', 'LIKE', "%{$search}%")
                    ->orWhere('contract_details.quantity', 'LIKE', "%{$search}%")
                    ->orWhere('contract_details.selling_price', 'LIKE', "%{$search}%")
                    ->orWhere('contracts.date', 'LIKE', "%{$search}%")
                    ->orWhere('contract_details.deadline', 'LIKE', "%{$search}%")
                    ->orWhere('manufacturerOrder.number', 'LIKE', "%{$search}%");

        } else {
            if(!empty($request->input('columns.0.search.value'))) {
                $search = $request->input('columns.0.search.value');
                $query =  $query->orWhere('contracts.name','LIKE',"%{$search}%");
            }
            if(!empty($request->input('columns.1.search.value'))) {
                $search = $request->input('columns.1.search.value');
                $query =  $query->orWhere('contracts.number', 'LIKE', "%{$search}%");
            }
            if(!empty($request->input('columns.2.search.value'))) {
                $search = $request->input('columns.2.search.value');
                $query =  $query->orWhere('prices.name', 'LIKE', "%{$search}%");
            }
            if(!empty($request->input('columns.3.search.value'))) {
                $search = $request->input('columns.3.search.value');
                $query =  $query->orWhere('contract_details.quantity', 'LIKE', "%{$search}%");
            }
            if(!empty($request->input('columns.4.search.value'))) {
                $search = $request->input('columns.4.search.value');
                $query =  $query->orWhere('contract_details.selling_price', 'LIKE', "%{$search}%");
            }
            if(!empty($request->input('columns.5.search.value'))) {
                $search = $request->input('columns.5.search.value');
                $query =  $query->orWhere('contracts.date', 'LIKE', "%{$search}%");
            }
            if(!empty($request->input('columns.6.search.value'))) {
                $search = $request->input('columns.6.search.value');
                $query =  $query->orWhere('contract_details.deadline', 'LIKE', "%{$search}%");
            }
            if(!empty($request->input('columns.7.search.value'))) {
                $search = $request->input('columns.7.search.value');
                $query =  $query->orWhere('manufacturerOrder.number', 'LIKE', "%{$search}%");
            }
        }

        $totalFiltered = $query->count();

        $contractDetails = $query->offset($start)
            ->limit($limit)
            ->orderBy($order, $dir)
            ->get();

        $data = [];

        if (!empty($contractDetails)) {
            foreach ($contractDetails as $contractDetail) {
                $show =  route('contracts.show',$contractDetail->id);
                $edit =  route('contracts.edit',$contractDetail->id);

                $nestedData['customer'] = $contractDetail->customer;
                $nestedData['number'] = $contractDetail->number;
                $nestedData['product'] = $contractDetail->product;
                $nestedData['quantity'] = $contractDetail->quantity;
                $nestedData['selling_price'] = $contractDetail->selling_price;
                $nestedData['date'] = $contractDetail->date;
                $nestedData['deadline'] = $contractDetail->deadline;
                $nestedData['order'] = $contractDetail->manufacturer ?? '';
                $nestedData['status'] = $contractDetail->status;
                $nestedData['view'] = "<a href='{$show}' title='Xem' class='btn btn-success'><i class=\"fa fa-tag\" aria-hidden=\"true\"></i> Xem</a>";
                $nestedData['edit'] = "<a href='{$edit}' title='Sửa' class='btn btn-primary'><i class=\"fa fa-pencil-square-o\" aria-hidden=\"true\"></i> Sửa</a>";
                $data[] = $nestedData;

            }
        }

        $json_data = [
            "draw"            => intval($request->input('draw')),
            "recordsTotal"    => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data"            => $data
        ];

        echo json_encode($json_data);


    }

}
