@extends('manufacturer-note._form')

@section('action', 'Tạo phiếu sản xuất')

@section('route')
    {{ route('manufacturer-notes.store') }}
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
            <select name="manufacturerNoteDetails[0][contract_detail_id]" class="form-control">
                <option value="" hidden>--Chọn sản phẩm--</option>
                @foreach( $manufacturerOrder->contract->contractDetails as $contractDetail)
                    @foreach( $contractDetail->price->product->boms as $bom )
                        <optgroup label="{{ $contractDetail->price->product->name }}">
                        @foreach( $bom->bomDetails as $bomDetail )
                            <option value="{{ $contractDetail->id }}" data-bom-id="{{ $bomDetail->product->id }}">{{ $bomDetail->product->name }}</option>
                        @endforeach
                        </optgroup>
                    @endforeach
                @endforeach
            </select>
        </td>
        <td data-col-seq="2">
            <select class="form-control" name="manufacturerNoteDetails[0][product_id]" required>
            </select>
        </td>
        <td data-col-seq="3">
            <input type="text" class="form-control" name="manufacturerNoteDetails[0][quantity]" required>
        </td>
        <td data-col-seq="4">
            <input type="text" class="form-control" name="manufacturerNoteDetails[0][note]">
        </td>
    </tr>
@endsection