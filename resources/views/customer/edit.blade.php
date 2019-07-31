@extends('customer._form')

@section('route')
    {{ route('customers.update', $customer) }}
@endsection

@section('action', 'Sửa thông tin khách hàng')

@section('method')
    @method('PATCH')
@stop