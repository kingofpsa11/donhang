@extends('shape-note._form')

@section('action', 'Tạo phiếu')

@section('route')
    {{ route('shape-notes.store') }}
@endsection

@section('table-body')
    <tr data-key="0">
        <td data-col-seq="0">1</td>
        <td data-col-seq="1">
            <p class="manufacturer-order-number"></p>
            <input type="hidden" name="details[0][contract_detail_id]">
        </td>
        <td data-col-seq="2">
            <select type="text" class="form-control manufacturer_note_detail_id" name="details[0][manufacturer_note_detail_id]" style="width:100%" required>
            </select>
            <div class="col-md-2 no-padding">
                <input type="text" class="form-control" name="details[0][code]" readonly>
            </div>
            <div class="col-md-10 no-padding">
                <select type="text" class="form-control" name="details[0][bom_id]" style="width:100%" required>
                </select>
            </div>
            <input type="hidden" name="details[0][product_id]">
        </td>
        <td data-col-seq="3">
            <input type="number" class="form-control" name="details[0][quantity]" required>
        </td>
        <td data-col-seq="4">
            <button class="btn btn-primary removeRow hidden"><i class="fa fa-minus" aria-hidden="true"></i></button>
        </td>
    </tr>
@endsection