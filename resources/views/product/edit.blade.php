@extends('product._form')

@section('route')
    {{ route('product.update', [$product]) }}
@endsection

@section('action', 'Tạo sản phẩm')

@section('method')
    PUT
@stop