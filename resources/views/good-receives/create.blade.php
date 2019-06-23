@extends('good-receives._form')

@section('route')
    {{ route('good-receive.store') }}
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
            <input type="text" class="form-control" readonly name="goodReceiveDetails[0][code]">
        </td>
        <td data-col-seq="2">
            <select class="form-control product_id" style="width: 100%;" name="goodReceiveDetails[0][product_id]" required>
            </select>
        </td>
        <td data-col-seq="3">
            <input type="text" class="form-control" name="goodReceiveDetails[0][unit]" readonly>
        </td>
        @role(4)
        <td data-col-seq="4">
            <select class="form-control bom_id" style="width: 100%;" name="goodReceiveDetails[0][bom_id]">
                <option value="">--Chọn định mức sản phẩm--</option>
            </select>
        </td>
        @endrole
        <td data-col-seq="5">
            <select class="form-control" style="width: 100%;" name="goodReceiveDetails[0][store_id]" required>
            </select>
        </td>
        <td data-col-seq="6">
            <input type="text" class="form-control" name="goodReceiveDetails[0][quantity]" required>
        </td>
        <td data-col-seq="6">
            <button class="btn btn-primary removeRow hidden"><i class="fa fa-minus" aria-hidden="true"></i></button>
        </td>
    </tr>
@endsection
