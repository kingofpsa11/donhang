<?php

namespace App\Http\Controllers;

use App\ExpenseOfPole;
use App\PoleWeight;
use Illuminate\Http\Request;

class PoleWeightController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $poleWeights = PoleWeight::with('poleWeightDetails')->get();
        return view('pole-weight.index', compact('poleWeights'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = ExpenseOfPole::all('name', 'id');
        return view('pole-weight.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $poleWeight = new PoleWeight();
        $poleWeight->fill($request->all())->save();
        foreach ($request->details as $detail) {
            $poleWeight->poleWeightDetails()->create($detail);
        }
        return redirect()->route('pole-weight.show', $poleWeight);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\PoleWeight  $poleWeight
     * @return \Illuminate\Http\Response
     */
    public function show(PoleWeight $poleWeight)
    {
        $poleWeight->load('poleWeightDetails', 'product', 'expenseOfPole');
        return view('pole-weight.show', compact('poleWeight'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\PoleWeight  $poleWeight
     * @return \Illuminate\Http\Response
     */
    public function edit(PoleWeight $poleWeight)
    {
        $poleWeight->load('poleWeightDetails', 'product', 'expenseOfPole');
        $categories = ExpenseOfPole::all('name', 'id');
        return view('pole-weight.edit', compact('poleWeight','categories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\PoleWeight  $poleWeight
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PoleWeight $poleWeight)
    {
        $poleWeight->update($request->all());
        $poleWeight->poleWeightDetails()->update(['status' => 9]);

        foreach ($request->details as $detail) {
            $id = $detail['id'];
            unset($detail['id']);
            $detail['status'] = 10;
            $poleWeight->poleWeightDetails()->updateOrCreate(['id' => $id], $detail);
        }

        $poleWeight->poleWeightDetails()->where('status',9)->delete();

        return redirect()->route('pole-weight.show', $poleWeight);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\PoleWeight  $poleWeight
     * @return \Illuminate\Http\Response
     */
    public function destroy(PoleWeight $poleWeight)
    {
        $poleWeight->delete();
        flash('Đã xóa bảng tính khối lượng của ' . $poleWeight->name)->success();
        return redirect()->route('pole-weight.index');
    }
}
