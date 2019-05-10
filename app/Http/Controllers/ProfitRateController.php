<?php

namespace App\Http\Controllers;

use App\Category;
use App\Customer;
use App\ProfitRate;
use Illuminate\Http\Request;

class ProfitRateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $profitRates = ProfitRate::orderBy('customer_id', 'asc')->get();
        return view('profit-rate.index')->with('profitRates', $profitRates);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $customers = Customer::all();
        $categories = Category::all();
        return view('profit-rate.create')->with(['customers' => $customers, 'categories' => $categories]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $category_id = $request->category_id;
        $customer_id = $request->customer_id;

        $checkExist = ProfitRate::where('category_id', $category_id)
                        ->where('customer_id', $customer_id)
                        ->first();
        if ($checkExist) {
            flash('Tỉ lệ đã tồn tại')->error();
            return redirect()->route('profit-rate.show', [$checkExist]);
        }

        $profitRate = new ProfitRate();
        $profitRate->category_id = $category_id;
        $profitRate->customer_id = $customer_id;
        $profitRate->rate = $request->input('rate');

        $profitRate->save();
        return redirect()->route('profit-rate.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\ProfitRate  $profitRate
     * @return \Illuminate\Http\Response
     */
    public function show(ProfitRate $profitRate)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ProfitRate  $profitRate
     * @return \Illuminate\Http\Response
     */
    public function edit(ProfitRate $profitRate)
    {
        $customers = Customer::all();
        $categories = Category::all();
        return view('profit-rate.edit')->with(['profitRate' => $profitRate, 'customers' => $customers, 'categories' => $categories]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ProfitRate  $profitRate
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ProfitRate $profitRate)
    {
        $category_id = $request->category_id;
        $customer_id = $request->customer_id;

        $checkExist = ProfitRate::where('category_id', $category_id)
            ->where('customer_id', $customer_id)
            ->first();
        if ($checkExist) {
            flash('Tỉ lệ đã tồn tại')->error();
            return redirect()->back()->withInput();
        }

        $profitRate->category_id = $request->category_id;
        $profitRate->customer_id = $request->customer_id;
        $profitRate->rate = $request->input('rate');

        $profitRate->save();
        return redirect()->route('profit-rate.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ProfitRate  $profitRate
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProfitRate $profitRate)
    {
        //
    }
}
