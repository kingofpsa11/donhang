<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InventoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = DB::table('good_delivery_details as d')
            ->where('d.actual_quantity', '>', 0)
            ->select('d.product_id')
            ->union(
                DB::table('good_receive_details as r')
                    ->select('r.product_id')
                    ->where('r.quantity', '>', 0)
            );

        $deliveries = DB::table('good_delivery_details AS d')
            ->select(
                'd.product_id',
                DB::raw('SUM(d.actual_quantity) as delivery_total')
            )
            ->where('d.actual_quantity', '>', 0)
            ->groupBy(
                'd.product_id'
            );

        $receive = DB::table('good_receive_details AS r')
            ->select(
                'r.product_id',
                DB::raw('SUM(r.quantity) as receive_total')
            )
            ->groupBy(
                'r.product_id'
            );

        $inventories = DB::table('products as p')
            ->joinSub($products, 'product', 'product.product_id', '=', 'p.id')
            ->leftJoinSub($receive, 'receive', 'p.id', '=', 'receive.product_id')
            ->leftJoinSub($deliveries, 'delivery', 'p.id', '=', 'delivery.product_id')
            ->select(
                'p.code',
                'p.name',
                DB::raw('IF(receive.receive_total IS NULL, 0, receive.receive_total) AS receive'),
                DB::raw('IF(delivery.delivery_total IS NULL, 0, delivery.delivery_total) AS delivery'),
                DB::raw('IF(receive.receive_total IS NULL, 0, receive.receive_total) - IF(delivery.delivery_total IS NULL, 0, delivery.delivery_total) AS total')
            )
            ->get();

        return view('inventory.index', compact('inventories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
