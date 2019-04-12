<?php

namespace App\Http\Controllers;

use App\Price;
use Illuminate\Http\Request;
use App\Customer;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $customers = Customer::all();
        $prices = Price::all();
        return view('contract.create')->with(['customers' => $customers, 'prices' => $prices]);
    }
}
