@extends('good-transfer._form')

@section('route')
    {{ route('good-transfer.store') }}
@endsection

@section('date')
    @php
        echo date('d/m/Y');
    @endphp
@stop

@section('table-body')
    <tr data-key="0">
        <td data-col-seq="0">1</td>
        <td data-col-seq="1">
            <select class="form-control product_id" style="width: 100%;" name="goodTransferDetails[0][product_id]" required>
            </select>
        </td>
        <td data-col-seq="2">
            <select class="form-control bom_id" style="width: 100%;" name="goodTransferDetails[0][bom_id]">
                <option value="">--Chọn định mức sản phẩm--</option>
            </select>
        </td>
        <td data-col-seq="3">
            <input type="text" class="form-control" name="goodTransferDetails[0][quantity]" required>
        </td>
        <td data-col-seq="4">
            <button class="btn btn-primary removeRow hidden"><i class="fa fa-minus" aria-hidden="true"></i></button>
        </td>
    </tr>
@endsection
