<?php

namespace App\Http\Controllers;

use App\Bom;
use App\BomDetail;
use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BomController extends Controller
{
    protected $bom;

    public function __construct(Bom $bom)
    {
        $this->bom = $bom;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $boms = Bom::with('product')->get();
        return view('bom.index',compact('boms'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('bom.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->bom->fill($request->all())->save();
        for ($i = 0; $i < count($request->code); $i++) {
            BomDetail::create([
                'bom_id' => $this->bom->id,
                'product_id' => $request->bom_product_id[$i],
                'quantity' => $request->quantity[$i],
            ]);
        }

        return redirect()->route('boms.show', $this->bom);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Bom  $bom
     * @return \Illuminate\Http\Response
     */
    public function show(Bom $bom)
    {
        return view('bom.show', compact('bom'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Bom  $bom
     * @return \Illuminate\Http\Response
     */
    public function edit(Bom $bom)
    {
        return view('bom.edit', compact('bom'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Bom  $bom
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Bom $bom)
    {
        $bom->update($request->all());
        $bom->bomDetails()->update(['status' => 9]);

        for ($i = 0; $i < count($request->code); $i++) {
            BomDetail::updateOrCreate(
                [
                    'id' => $request->bom_detail_id[$i]
                ],
                [
                    'bom_id' => $bom->id,
                    'product_id' => $request->bom_product_id[$i],
                    'quantity' => $request->quantity[$i],
                    'status' => 10
                ]
            );

            $bom->bomDetails()->where('status', 9)->delete();
        }

        return redirect()->route('boms.show', $bom);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Bom  $bom
     * @return \Illuminate\Http\Response
     */
    public function destroy(Bom $bom)
    {
        $bom->delete();
        flash('Đã xóa định mức sản phẩm: ' . $bom->product->name)->success();
        return redirect()->route('boms.index');
    }

    public function getBom(Request $request)
    {
        $bom = DB::table('boms')
            ->join('bom_details AS bd', 'boms.id', '=', 'bd.bom_id')
//            ->join('boms as b', 'b.product_id', '=', 'bd.product_id')
            ->join('products AS p', 'p.id','=', 'bd.product_id')
            ->where('boms.product_id', $request->productId)
            ->select('bd.product_id', 'p.name AS product_name', 'p.code', 'bd.quantity', 'boms.name', 'boms.id as bom_id')
            ->get();

        return response()->json($bom);
    }
}
