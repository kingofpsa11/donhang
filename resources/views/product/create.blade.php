@extends('product._form')

@section('route')
    {{ route('products.store') }}
@endsection

@section('action', 'Tạo sản phẩm')