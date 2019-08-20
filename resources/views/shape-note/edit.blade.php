@extends('shape-note._form')

@section('action', 'Sửa phiếu')

@section('route')
    {{ route('shape-notes.update', $shapeNote) }}
@endsection

@section('method')
    @method('PATCH')
@stop

@section('table-body')
    @foreach ($shapeNote->shapeNoteDetails as $shapeNoteDetail)
        @php($i = $loop->index)
        <tr data-key="{{ $i }}">
            <td data-col-seq="0">
                <span>{{ $loop->iteration }}</span>
                <input type="hidden" name="details[{{ $i }}][id]" value="{{ $shapeNoteDetail->id }}">
            </td>
            <td data-col-seq="1">
                <p class="manufacturer-order-number">{{ $shapeNoteDetail->contractDetail->manufacturerOrderDetail->manufacturerOrder->number }}</p>
                <input type="hidden" name="details[{{ $i }}][contract_detail_id]" value="{{ $shapeNoteDetail->contract_detail_id }}">
            </td>
            <td data-col-seq="3">
                <select type="text" class="form-control manufacturer_note_detail_id" name="details[{{ $i }}][manufacturer_note_detail_id]" style="width:100%" required>
                    <option value="{{ $shapeNoteDetail->manufacturer_note_detail_id }}">{{ $shapeNoteDetail->manufacturerNoteDetail->product->name }}</option>
                </select>
                <div class="col-md-2 no-padding">
                    <input type="text" class="form-control" name="details[{{ $i }}][code]" value="{{ $shapeNoteDetail->product->code }}" readonly>
                </div>
                <div class="col-md-10 no-padding">
                    <select type="text" class="form-control" name="details[{{ $i }}][bom_id]" style="width:100%" required>
                        <option value="{{ $shapeNoteDetail->bom_id }}">{{ $shapeNoteDetail->product->name }}</option>
                    </select>
                </div>
                <input type="hidden" name="details[{{ $i }}][product_id]" value="{{ $shapeNoteDetail->product_id }}">
            </td>
            <td data-col-seq="4">
                <input type="number" class="form-control" name="details[{{ $i }}][quantity]" value="{{ $shapeNoteDetail->quantity }}" required>
            </td>
            <td data-col-seq="5">
                <button class="btn btn-primary removeRow hidden"><i class="fa fa-minus" aria-hidden="true"></i></button>
            </td>
        </tr>
    @endforeach
@endsection
