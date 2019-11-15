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

    public function getUnitPrice(Request $request)
    {
        $id = $request->category;
        $category = ExpenseOfPole::find($id);
        $gia_thep = $category->gia_thep;
        $du_phong_vat_tu = $category->du_phong_vat_tu;
        $vat_tu_phu = $category->vat_tu_phu;
        $hao_phi = $category->hao_phi;
        $nhan_cong_truc_tiep = $category->nhan_cong_truc_tiep * $request->ty_le_nhan_cong;
        $chi_phi_chung = $category->chi_phi_chung;
        $chi_phi_ma_kem = $category->chi_phi_ma_kem;
        $chi_phi_van_chuyen = $category->chi_phi_van_chuyen;
        $lai = $category->lai;

        $unit_price = round((($gia_thep * (1 + ($du_phong_vat_tu + $vat_tu_phu + $hao_phi)/100) + $nhan_cong_truc_tiep * 1.3) * (1 + $chi_phi_chung/100) + $chi_phi_ma_kem + $chi_phi_van_chuyen) * (1 + $lai/100), -2);
        return $unit_price;
    }
}
