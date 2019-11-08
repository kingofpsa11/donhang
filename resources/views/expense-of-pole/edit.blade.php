@extends('product._form')

@section('route')
    {{ route('products.update', $product) }}
@endsection

@section('action', 'Tạo sản phẩm')

@section('method')
    @method('PATCH')
@stop