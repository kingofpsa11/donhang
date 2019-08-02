<?php

namespace App\Http\Controllers;

use App\Services\GoodReceiveService;
use App\GoodDelivery;
use App\GoodReceiveDetail;
use App\GoodReceive;
use App\GoodDeliveryDetail;
use App\Role;
use App\Bom;
use Carbon\Carbon;
use Illuminate\Http\Request;

class GoodReceiveController extends Controller
{
    protected $goodReceiveService;

    public function __construct(GoodReceiveService $goodReceiveService)
    {
        $this->goodReceiveService = $goodReceiveService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $goodReceiveDetails = $this->goodReceiveService->index();
        return view('good-receive.index', compact('goodReceiveDetails'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $newNumber = $this->goodReceiveService->getNewNumber();
        $roles = Role::find([4,5]);
        return view('good-receive.create', compact('newNumber', 'roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $goodReceive = $this->goodReceiveService->create($request);

        return redirect()->route('good-receive.show', $goodReceive);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\GoodReceive  $goodReceive
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $goodReceive = $this->goodReceiveService->show($id);

        return view('good-receive.show', compact('goodReceive'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\GoodReceive  $goodReceive
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $goodReceive = $this->goodReceiveService->show($id);

        return view('good-receive.edit', compact('goodReceive'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\GoodReceive  $goodReceive
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->goodReceiveService->update($request, $id);
        $goodReceive = $this->goodReceiveService->find($id);
        return view('good-receive.show', compact('goodReceive'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\GoodReceive  $goodReceive
     * @return \Illuminate\Http\Response
     */
    public function destroy(GoodReceive $goodReceive)
    {
        $goodReceive->delete();
        flash('Đã xóa phiếu nhập ' . $goodReceive->number)->warning();
        return redirect()->route('good-receive.index');
    }

    public function getNewNumber()
    {

    }
}
