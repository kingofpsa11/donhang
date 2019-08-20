@extends('manufacturer-note._form')

@section('action', 'Tạo phiếu cắt phôi')

@section('route')
    {{ route('manufacturer-notes.store') }}
@endsection

@section('table-body')
    <tr data-key="0">
        <td data-col-seq="0">
            <span>1</span>
        </td>
        <td data-col-seq="1">
            <p class="manufacturer-order-number"></p>
        </td>
        <td data-col-seq="2">
            <select name="details[0][contract_detail_id]" class="form-control" style="width: 100%" required>
            </select>
            <select class="form-control" name="details[0][product_id]" style="width: 100%" required>
            </select>
        </td>
        <td data-col-seq="3">
            <input type="text" class="form-control" name="details[0][length]" required>
        </td>
        <td data-col-seq="3">
            <input type="text" class="form-control" name="details[0][thickness]" required>
        </td>
        <td data-col-seq="3">
            <input type="text" class="form-control" name="details[0][top_perimeter]" required>
        </td>
        <td data-col-seq="3">
            <input type="text" class="form-control" name="details[0][bottom_perimeter]" required>
        </td>
        <td data-col-seq="3">
            <input type="text" class="form-control" name="details[0][quantity]" required>
        </td>
        <td data-col-seq="4">
            <input type="text" class="form-control" name="details[0][note]">
        </td>
        <td data-col-seq="5">
            <button class="btn btn-primary removeRow hidden"><i class="fa fa-minus" aria-hidden="true"></i></button>
        </td>
    </tr>
@endsection