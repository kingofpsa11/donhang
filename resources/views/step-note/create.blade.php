@extends('step-note._form')

@section('action', 'Tạo phiếu')

@section('route')
    {{ route('step-notes.store') }}
@endsection

@section('table-body')
    <tr data-key="0">
        <td data-col-seq="0">
            <span>1</span>
        </td>
{{--        <td data-col-seq="1">--}}
{{--            <input type="text" class="form-control manufacturer" name="manufacturer_order_number[]" readonly>--}}
{{--        </td>--}}
{{--        <td data-col-seq="2">--}}
{{--            <input type="text" class="form-control" name="code[]" readonly>--}}
{{--        </td>--}}
{{--        <td data-col-seq="3">--}}
{{--            <input type="hidden" name="contract_detail_id[]">--}}
{{--            <select type="text" class="form-control select2 product_id" name="product_id[]" style="width:100%" required>--}}
{{--            </select>--}}
{{--        </td>--}}
{{--        <td data-col-seq="4">--}}
{{--            <div class="form-group has-warning">--}}
{{--                <input type="number" class="form-control" name="quantity[]" value="{{ old('quantity')[0] }}">--}}
{{--                <span id="helpBlock" class="help-block"></span>--}}
{{--            </div>--}}
{{--        </td>--}}
        <td data-col-seq="1">
            <input type="text" class="form-control manufacturer" name="details[0][manufacturer_order_number]" readonly>
        </td>
        <td data-col-seq="2">
            <input type="text" class="form-control" name="details[0][code]" readonly>
        </td>
        <td data-col-seq="3">
            <input type="hidden" name="details[0][contract_detail_id]">
            <select type="text" class="form-control select2 product_id" name="details[0][product_id]" style="width:100%" required>
            </select>
        </td>
        <td data-col-seq="4">
            <div class="form-group has-warning">
                <input type="number" class="form-control" name="details[0][quantity]">
                <span id="helpBlock" class="help-block"></span>
            </div>
        </td>
        <td data-col-seq="5">
            <button class="btn btn-primary removeRow hidden"><i class="fa fa-minus" aria-hidden="true"></i></button>
        </td>
    </tr>
@endsection