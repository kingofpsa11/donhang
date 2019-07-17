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
        <td>
            <span>1</span>
        </td>
        <td data-col-seq="1">
            <select name="manufacturerNoteDetails[0][contract_detail_id]" class="form-control">
                @foreach( $manufacturerOrder->contract->contractDetails as $contractDetails)
                    <option value="{{ $contractDetails->id }}">{{ $contractDetails->price->product->name }}</option>
                @endforeach
            </select>
        </td>
        <td data-col-seq="2">
            <select type="text" class="form-control" name="manufacturerNoteDetails[0][product_id]" style="width:100%" required>
            </select>
        </td>
        <td data-col-seq="3">
            <input type="number" class="form-control" name="manufacturerNoteDetails[0][quantity]" required>
        </td>
        <td data-col-seq="4">
            <input type="text" class="form-control" name="manufacturerNoteDetails[0][note]">
        </td>
    </tr>
@endsection