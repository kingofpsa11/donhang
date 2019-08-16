@extends('output_order._form')

@section('action', 'Tạo lệnh xuất hàng')

@section('route')
    {{ route('output-orders.store') }}
@endsection

@section('table-body')
    <tr data-key="0">
        <td data-col-seq="0">1</td>
        <td data-col-seq="1">
            <p class="contract-number"></p>
        </td>
        <td data-col-seq="2">
            <p class="manufacturer-order-number"></p>
        </td>
        <td data-col-seq="3">
            <p class="code"></p>
            <input type="hidden" name="code[]" readonly>
        </td>
        <td data-col-seq="4">
            <select type="text" class="form-control select2 contract" name="contract_detail_id[]" style="width:100%" required>
            </select>
        </td>
        <td data-col-seq="5">
            <input type="text" class="form-control" name="quantity[]">
        </td>
        <td data-col-seq="6">
            <input type="text" class="form-control" name="note[]">
        </td>
        <td data-col-seq="7">
            <button class="btn btn-primary removeRow hidden"><i class="fa fa-minus" aria-hidden="true"></i></button>
        </td>
    </tr>
@endsection