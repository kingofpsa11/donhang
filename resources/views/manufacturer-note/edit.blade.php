@extends('manufacturer-note._form')

@section('action', 'Sửa phiếu')

@section('route')
    {{ route('manufacturer-notes.update', $manufacturerNote) }}
@endsection

@section('method')
    @method('PATCH')
@stop

@section('table-body')
    @foreach ($manufacturerNote->manufacturerNoteDetails as $manufacturerNoteDetail)
        <tr data-key="{{ $loop->index }}">
            <td data-col-seq="0">
                <span>{{ $loop->iteration }}</span>
                <input type="hidden" name="manufacturer_note_detail_id[]" value="{{ $manufacturerNoteDetail->id }}">
            </td>
            <td data-col-seq="1">
                <p class="manufacturer-order-number">{{ $manufacturerNoteDetail->contractDetail->manufacturerOrderDetail->manufacturerOrder->number }}</p>
            </td>
            <td data-col-seq="2">
                <select name="contract_detail_id[]" class="form-control" style="width: 100%">
                    <option value="{{ $manufacturerNoteDetail->contract_detail_id }}">{{ $manufacturerNoteDetail->contractDetail->price->product->name }}</option>
                </select>
                <select class="form-control" name="product_id[]" style="width: 100%" required>
                    <option value="{{ $manufacturerNoteDetail->product_id }}">{{ $manufacturerNoteDetail->product->name }}</option>
                </select>
            </td>
            <td data-col-seq="3">
                <input type="text" class="form-control" name="length[]" value="{{ $manufacturerNoteDetail->length }}" required>
            </td>
            <td data-col-seq="3">
                <input type="text" class="form-control" name="thickness[]" value="{{ $manufacturerNoteDetail->thickness }}" required>
            </td>
            <td data-col-seq="3">
                <input type="text" class="form-control" name="top_perimeter[]" value="{{ $manufacturerNoteDetail->top_perimeter }}" required>
            </td>
            <td data-col-seq="3">
                <input type="text" class="form-control" name="bottom_perimeter[]" value="{{ $manufacturerNoteDetail->bottom_perimeter }}" required>
            </td>
            <td data-col-seq="3">
                <input type="text" class="form-control" name="quantity[]" value="{{ $manufacturerNoteDetail->quantity }}" required>
            </td>
            <td data-col-seq="4">
                <input type="text" class="form-control" name="note[]" value="{{ $manufacturerNoteDetail->note }}">
            </td>
            <td data-col-seq="5">
                <button class="btn btn-primary removeRow hidden"><i class="fa fa-minus" aria-hidden="true"></i></button>
            </td>
        </tr>
    @endforeach
@endsection