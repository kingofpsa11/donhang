@extends('boms._form')

@section('route')
    {{ route('bom.store') }}
@endsection

@section('table-body')
    <tr data-key="0">
        <td data-col-seq="0">1</td>
        <td class="col-md-1" data-col-seq="1">
            <input type="text" class="form-control input-sm" readonly="" name="bom_details[0][code]">
        </td>
        <td class="col-md-6" data-col-seq="2">
            <select name="bom_details[0][product_id]" required class="form-control" style="width: 100%;"></select>
        </td>
        <td class="col-md-2" data-col-seq="3">
            <input type="text" class="form-control input-sm" name="bom_details[0][quantity]" required>
        </td>
        <td class="col-md-3" data-col-seq="4">
            <input type="text" class="form-control input-sm" name=bom_details[0][note]>
        </td>
        <td data-col-seq="5">
            <button class="btn btn-primary removeRow hidden"><i class="fa fa-minus" aria-hidden="true"></i></button>
        </td>
    </tr>
@endsection
