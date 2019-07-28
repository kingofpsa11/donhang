@extends('price._form')

@section('route')
    {{ route('prices.update', $price) }}
@endsection

@section('action', 'Tạo giá')

@section('method')
    @method('PATCH')
@stop