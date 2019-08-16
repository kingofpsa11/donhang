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
            <input type="hidden" name="contract_detail_id[]">
        </td>
        <td data-col-seq="3">
            <select type="text" class="form-control contract_detail_id" name="manufacturer_note_detail_id[]" style="width:100%" required>
            </select>
            <div class="col-md-2 no-padding">
                <input type="text" class="form-control" name="code[]" readonly>
            </div>
            <div class="col-md-10 no-padding">
                <select type="text" class="form-control" name="product_id[]" style="width:100%" id="product_id" required>
                </select>
            </div>
        </td>
        <td data-col-seq="4">
            <input type="number" class="form-control" name="quantity[]">
        </td>
        <td data-col-seq="5">
            <button class="btn btn-primary removeRow hidden"><i class="fa fa-minus" aria-hidden="true"></i></button>
        </td>
    </tr>
@endsection