@extends('profit-rate._form')

@section('route')
    {{ route('profit-rate.update', [$profitRate]) }}
@endsection

@section('method')
    @method('PUT')
@stop