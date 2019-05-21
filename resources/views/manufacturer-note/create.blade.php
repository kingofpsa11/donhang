@extends('manufacturer-note._form')

@section('action', 'Tạo phiếu sản xuất')

@section('route')
    {{ route('manufacturer-note.store') }}
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
            <input class="form-control" readonly name="manufacturerNoteDetails[0][manufacturer_order_number]">
        </td>
        <td data-col-seq="2">
            <input type="hidden" name="manufacturerNoteDetails[0][product_id]">
            <select type="text" class="form-control contract_detail" name="manufacturerNoteDetails[0][contract_detail_id]" style="width:100%" required>
            </select>
        </td>
        <td data-col-seq="3">
            <select type="text" class="form-control bom" name="manufacturerNoteDetails[0][bom_id]" style="width:100%" required>
                <option value="">--Chọn định mức của sản phẩm--</option>
            </select>
        </td>
        <td data-col-seq="4">
            <input type="number" class="form-control" name="manufacturerNoteDetails[0][quantity]" required>
        </td>
        <td data-col-seq="5">
            <input type="text" class="form-control" name="manufacturerNoteDetails[0][note]">
        </td>
        <td data-col-seq="6">
            <button class="btn btn-primary removeRow hidden"><i class="fa fa-minus" aria-hidden="true"></i></button>
        </td>
    </tr>
@endsection

@section('javascript')
    @parent
        });
    </script>
@stop
