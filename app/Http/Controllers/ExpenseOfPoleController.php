<?php

namespace App\Http\Controllers;

use App\ExpenseOfPole;
use Illuminate\Http\Request;

class ExpenseOfPoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $expenses = ExpenseOfPole::all();
        return view('expense-of-pole.index', compact('expenses'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('expense-of-pole.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $expenseOfPole = new ExpenseOfPole;
        $expenseOfPole->fill($request->all())->save();

        return redirect()->route('expense-of-pole.show', $expenseOfPole);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\ExpenseOfPole  $expenseOfPole
     * @return \Illuminate\Http\Response
     */
    public function show(ExpenseOfPole $expenseOfPole)
    {
        return view('expense-of-pole.show', compact('expenseOfPole'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ExpenseOfPole  $expenseOfPole
     * @return \Illuminate\Http\Response
     */
    public function edit(ExpenseOfPole $expenseOfPole)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ExpenseOfPole  $expenseOfPole
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ExpenseOfPole $expenseOfPole)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ExpenseOfPole  $expenseOfPole
     * @return \Illuminate\Http\Response
     */
    public function destroy(ExpenseOfPole $expenseOfPole)
    {
        //
    }
}
