<?php

namespace App\Http\Controllers;

use App\Price;
use App\Product;
use App\ProfitRate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PriceController extends Controller
{
    protected $price;

    public function __construct(Price $price)
    {
        $this->price = $price;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $prices = Price::with('product')->get();
        return view('price.index', compact('prices'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Product $product)
    {
        $price = $this->price->where('product_id', $product->id)->first();
        if ($price) {
            return redirect()->route('prices.edit', $price);
        } else {
            return view('price.create', compact('product'));
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->price->fill($request->all())->save();
        return redirect()->route('prices.show', $this->price);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Price  $price
     * @return \Illuminate\Http\Response
     */
    public function show(Price $price)
    {
        return response()->view('price.show', compact('price'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Price  $price
     * @return \Illuminate\Http\Response
     */
    public function shows(Request $request)
    {
        $term = $request->search;
        $customer = $request->customer_id;
        $profitRate = ProfitRate::where('customer_id', $customer)->first();

        if ($profitRate) {
            $result = DB::table('prices')
                ->join('products', 'prices.product_id', '=', 'products.id')
                ->leftJoin('profit_rates', 'profit_rates.category_id', '=', 'products.category_id')
                ->select('prices.id', 'products.name', 'products.code', DB::raw('(prices.selling_price * profit_rates.rate) as sell_price'))
                ->where('profit_rates.customer_id', '=', $customer)
                ->where('products.name', 'LIKE', '%' . $term . '%')
                ->take(20)
                ->get();
        } else {
            $result = DB::table('prices')
                ->join('products', 'prices.product_id', '=', 'products.id')
                ->select('prices.id', 'products.name', 'products.code', DB::raw('prices.selling_price as sell_price'))
                ->where('products.name', 'LIKE', '%' . $term . '%')
                ->take(20)
                ->get();
        }

        return response()->json($result);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Price  $price
     * @return \Illuminate\Http\Response
     */
    public function edit(Price $price)
    {
        return response()->view('price.edit', compact('price'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Price  $price
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Price $price)
    {
        $price->update($request->all());
        return redirect()->route('prices.show', $price);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Price  $price
     * @return \Illuminate\Http\Response
     */
    public function destroy(Price $price)
    {
        //
    }
}
