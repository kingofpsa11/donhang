<?php

namespace App\Http\Controllers;

use App\Category;
use App\Product;
use App\Services\ProductService;
use DebugBar\DebugBar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    protected $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('product.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::all();
        return view('product.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $product = $this->productService->create($request);

        return redirect()->route('products.show', $product);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = $this->productService->find($id);
        \Debugbar::info('Error');
        return view('product.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('product.edit',compact('product', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->productService->update($request, $id);

        return redirect()->route('products.show', $id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        $product->delete();
        flash('Đã xóa sản phẩm' . $product->name)->success();
        return redirect()->route('products.index');
    }

    public function deleteImage(Request $request)
    {
        $this->productService->deleteImage($request->id);
    }

    public function getProduct(Request $request)
    {
        $term = $request->term;

        $products = Product::where('name', 'LIKE', '%' . $term . '%')
            ->select('id', 'name', 'code')
            ->orderBy('id')
            ->get();

        return response()->json($products);
    }

    public function existCode(Request $request)
    {
        $code = $request->code;

        return $this->productService->existCode($code);
    }

    public function allProducts(Request $request)
    {
        $columns = array(
            0 =>'category',
            1 =>'code',
            2=> 'name',
            3=> 'status',
            4=> 'view',
            5=> 'edit',
        );

        $totalData = Product::count();

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');


        $query = DB::table('products')
            ->leftJoin('categories', 'categories.id','=','products.category_id')
            ->select(
                'categories.name AS category',
                'products.*'
            );

        if (empty($request->input('search.value')) && !array_filter(array_column(array_column($request->columns, 'search'), 'value'))) {

        } elseif (!empty($request->input('search.value'))) {

            $search = $request->input('search.value');

            $query = $query
                ->where('products.name', 'LIKE', "%{$search}%")
                ->orWhere('products.name_bill', 'LIKE', "%{$search}%")
                ->orWhere('products.code', 'LIKE', "%{$search}%")
                ->orWhere('categories.name', 'LIKE', "%{$search}%");

        } else {
            if(!empty($request->input('columns.0.search.value'))) {
                $search = $request->input('columns.0.search.value');
                $query =  $query->orWhere('categories.name', 'LIKE', "%{$search}%");
            }
            if(!empty($request->input('columns.1.search.value'))) {
                $search = $request->input('columns.1.search.value');
                $query =  $query->orWhere('products.code', 'LIKE', "%{$search}%");
            }
            if(!empty($request->input('columns.2.search.value'))) {
                $search = $request->input('columns.2.search.value');
                $query =  $query->where('products.name', 'LIKE', "%{$search}%");
            }
        }

        $totalFiltered = $query->count();

        $query = $query->offset($start)
            ->limit($limit)
            ->orderBy($order, $dir);

        $products = $query->get();

        $data = [];

        if (!empty($products)) {
            foreach ($products as $product) {
                $show =  route('products.show',$product->id);
                $edit =  route('products.edit',$product->id);

                $nestedData['category'] = $product->category;
                $nestedData['code'] = $product->code;
                $nestedData['name'] = $product->name;
                $nestedData['status'] = $product->status;
                $nestedData['view'] = "<a href='{$show}' title='Xem' class='btn btn-success'><i class=\"fa fa-tag\" aria-hidden=\"true\"></i> Xem</a>";
                $nestedData['edit'] = "<a href='{$edit}' title='Sửa' class='btn btn-primary'><i class=\"fa fa-pencil-square-o\" aria-hidden=\"true\"></i> Sửa</a>";
                $data[] = $nestedData;

            }
        }

        $json_data = [
            "draw"            => intval($request->input('draw')),
            "recordsTotal"    => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data"            => $data
        ];

        echo json_encode($json_data);
    }
}

