@extends('output_order.form')

@section('route')
    {{ route('output-order.store') }}
@endsection

@section('output-order-date')
    @php
        echo date('d/m/Y');
    @endphp
@stop

@section('table-body')
    <tr data-key="0">
        <td data-col-seq="0">1</td>
        <td class="col-md-1" data-col-seq="1">
            <div class="form-group">
                <input class="form-control input-sm" name="outputOrderDetails[0][contract_id]" readonly>
            </div>
        </td>
        <td class="col-md-1" data-col-seq="2">
            <div class="form-group">
                <input type="text" class="form-control input-sm" name="outputOrderDetails[0][manufacturer_order_number]" readonly>

            </div>
        </td>
        <td class="col-md-2" data-col-seq="3">
            <div class="form-group">
                <input type="text" class="form-control input-sm" name="outputOrderDetails[0][code]" readonly>

            </div>
        </td>
        <td class="col-md-5" data-col-seq="4">
            <div class="form-group">
                <select type="text" class="form-control input-sm select2 contract" name="outputOrderDetails[0][contract_detail_id]" style="width:100%" required>
                </select>
            </div>
        </td>
        <td class="col-md-1" data-col-seq="5">
            <div class="form-group">
                <input type="number" class="form-control input-sm" name="outputOrderDetails[0][quantity]">
            </div>
        </td>
        <td class="col-md-2" data-col-seq="6">
            <div class="form-group">
                <input type="text" class="form-control input-sm" name="outputOrderDetails[0][note]">
            </div>
        </td>
        <td data-col-seq="7">
            <button class="btn btn-primary addProduct"><i class="fa fa-plus" aria-hidden="true"></i></button>
        </td>
    </tr>
@endsection
