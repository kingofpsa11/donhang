@extends('customer._form')

@section('route')
    {{ route('customers.store') }}
@endsection

@section('action', 'Tạo khách hàng mới')