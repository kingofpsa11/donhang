@extends('step-note._form')

@section('action', 'Sửa phiếu')

@section('route')
    {{ route('step-notes.update', $stepNote) }}
@endsection

@section('method')
    @method('PATCH')
@stop

@section('table-body')
    @foreach ($stepNote->stepNoteDetails as $stepNoteDetail)
        <tr data-key="{{ $loop->index }}">
            <td data-col-seq="0">
                <span>{{ $loop->iteration }}</span>
                <input type="hidden" name="step_note_detail_id[]" value="{{ $stepNoteDetail->id }}">
            </td>
            <td data-col-seq="1">
                <input class="form-control" name="manufacturer_order_number[]" readonly value="{{ $stepNoteDetail->contractDetail->manufacturerOrderDetail->manufacturerOrder->number }}">
            </td>
            <td data-col-seq="2">
                <input type="text" class="form-control" name="code[]" readonly value="{{ $stepNoteDetail->product->code }}">
            </td>
            <td data-col-seq="3">
                <input type="hidden" name="contract_detail_id[]">
                <select type="text" class="form-control select2" name="product_id[]" style="width:100%" id="product_id" required>
                    <option value="{{ $stepNoteDetail->product_id }}">{{ $stepNoteDetail->product->name }}</option>
                </select>
            </td>
            <td data-col-seq="5">
                <input type="number" class="form-control" name="quantity[]" value="{{ $stepNoteDetail->quantity }}">
            </td>
            <td data-col-seq="7">
                <button class="btn btn-primary removeRow"><i class="fa fa-minus" aria-hidden="true"></i></button>
            </td>
        </tr>
    @endforeach
@endsection
