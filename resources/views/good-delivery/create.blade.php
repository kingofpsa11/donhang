@extends('good-delivery._form')

@section('route')
    {{ route('good-deliveries.store') }}
@endsection

@section('date')
    @php
        echo date('d/m/Y');
    @endphp
@stop

@section('table-body')
    <tr data-key="0">
        <td data-col-seq="0">
            <span>1</span>
        </td>
        <td data-col-seq="1">
            <input type="text" class="form-control" readonly name="code[]">
        </td>
        <td data-col-seq="2">
            <select class="form-control product_id" style="width: 100%;" name="product_id[]" required>
            </select>
        </td>
        <td data-col-seq="3">
            <input type="text" class="form-control" name="unit[]" readonly>
        </td>
        <td data-col-seq="4">
            <select class="form-control" style="width: 100%;" name="store_id[]" required>
            </select>
        </td>
        <td data-col-seq="5">
            <input type="text" class="form-control" name="actual_quantity[]" required>
        </td>
        <td data-col-seq="6">
            <button class="btn btn-primary removeRow hidden"><i class="fa fa-minus" aria-hidden="true"></i></button>
        </td>
    </tr>
@endsection
