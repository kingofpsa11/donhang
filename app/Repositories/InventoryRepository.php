<?php
namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class InventoryRepository
{
    public function all()
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

        return $inventories;
    }

    public function allWithStore()
    {
        $products = DB::table('good_delivery_details as d')
            ->where('d.actual_quantity', '>', 0)
            ->select('d.product_id', 'stores.name', 'stores.id')
            ->join('stores', 'stores.id', 'd.store_id')
            ->union(
                DB::table('good_receive_details as r')
                    ->select('r.product_id', 'stores.name', 'stores.id')
                    ->where('r.quantity', '>', 0)
                    ->join('stores', 'stores.id', 'r.store_id')
            );


        $deliveries = DB::table('good_delivery_details AS d')
            ->select(
                'd.product_id',
                'd.store_id',
                DB::raw('SUM(d.actual_quantity) as delivery_total')
            )
            ->where('d.actual_quantity', '>', 0)
            ->groupBy(
                'd.product_id',
                'd.store_id'
            );

        $receive = DB::table('good_receive_details AS r')
            ->select(
                'r.product_id',
                'r.store_id',
                DB::raw('SUM(r.quantity) as receive_total')
            )
            ->groupBy(
                'r.product_id',
                'r.store_id'
            );

        $inventories = DB::table('products as p')
            ->joinSub($products, 'product', 'product.product_id', '=', 'p.id')
            ->leftJoinSub($receive, 'receive', function ($join) {
                $join->on('p.id', '=', 'receive.product_id')
                    ->on('product.id', '=', 'receive.store_id');
            })
            ->leftJoinSub($deliveries, 'delivery', function ($join) {
                $join->on('p.id', '=', 'delivery.product_id')
                    ->on('product.id', '=', 'delivery.store_id');
            })
            ->select(
                'p.code',
                'p.name as product_name',
                'product.name as store_name',
                DB::raw('IF(receive.receive_total IS NULL, 0, receive.receive_total) AS receive'),
                DB::raw('IF(delivery.delivery_total IS NULL, 0, delivery.delivery_total) AS delivery'),
                DB::raw('IF(receive.receive_total IS NULL, 0, receive.receive_total) - IF(delivery.delivery_total IS NULL, 0, delivery.delivery_total) AS total')
            )
            ->get();

        return $inventories;
    }
    public function checkInventory($product = null)
    {
        $products = DB::table('good_delivery_details as d')
            ->where('d.actual_quantity', '>', 0)
            ->select('d.product_id', 'stores.name', 'stores.id')
            ->join('stores', 'stores.id', 'd.store_id')
            ->union(
                DB::table('good_receive_details as r')
                    ->select('r.product_id', 'stores.name', 'stores.id')
                    ->where('r.quantity', '>', 0)
                    ->join('stores', 'stores.id', 'r.store_id')
            );


        $deliveries = DB::table('good_delivery_details AS d')
            ->select(
                'd.product_id',
                'd.store_id',
                DB::raw('SUM(d.actual_quantity) as delivery_total')
            )
            ->where('d.actual_quantity', '>', 0)
            ->groupBy(
                'd.product_id',
                'd.store_id'
            );

        $receive = DB::table('good_receive_details AS r')
            ->select(
                'r.product_id',
                'r.store_id',
                DB::raw('SUM(r.quantity) as receive_total')
            )
            ->groupBy(
                'r.product_id',
                'r.store_id'
            );

        $inventories = DB::table('products as p')
            ->joinSub($products, 'product', 'product.product_id', '=', 'p.id')
            ->leftJoinSub($receive, 'receive', function ($join) {
                $join->on('p.id', '=', 'receive.product_id')
                    ->on('product.id', '=', 'receive.store_id');
            })
            ->leftJoinSub($deliveries, 'delivery', function ($join) {
                $join->on('p.id', '=', 'delivery.product_id')
                    ->on('product.id', '=', 'delivery.store_id');
            })
            ->select(
                'p.code',
                'p.id',
                'p.name as product_name',
                'product.name as store_name',
                DB::raw('IF(receive.receive_total IS NULL, 0, receive.receive_total) AS receive'),
                DB::raw('IF(delivery.delivery_total IS NULL, 0, delivery.delivery_total) AS delivery'),
                DB::raw('IF(receive.receive_total IS NULL, 0, receive.receive_total) - IF(delivery.delivery_total IS NULL, 0, delivery.delivery_total) AS total')
            )
            ->when($product, function ($query, $product) {
                return $query->where('p.id', $product);
            })
            ->get();

        return $inventories;

    }
}