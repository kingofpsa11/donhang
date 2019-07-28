@extends('price._form')

@section('route')
    {{ route('prices.store') }}
@endsection

@section('action', 'Tạo giá')