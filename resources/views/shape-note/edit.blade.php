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
        <tr data-key="{{ $loop->index }}">
            <td data-col-seq="0">
                <span>{{ $loop->iteration }}</span>
                <input type="hidden" name="shape_note_detail_id[]" value="{{ $shapeNoteDetail->id }}">
            </td>
            <td data-col-seq="1">
                <p class="manufacturer-order-number">{{ $shapeNoteDetail->contractDetail->manufacturerOrderDetail->manufacturerOrder->number }}</p>
                <input type="hidden" name="contract_detail_id[]" value="{{ $shapeNoteDetail->contract_detail_id }}">
            </td>
            <td data-col-seq="3">
                <select type="text" class="form-control manufacturer_note_detail_id" name="manufacturer_note_detail_id[]" style="width:100%" required>
                    <option value="{{ $shapeNoteDetail->manufacturer_note_detail_id }}">{{ $shapeNoteDetail->manufacturerNoteDetail->product->name }}</option>
                </select>
                <div class="col-md-2 no-padding">
                    <input type="text" class="form-control" name="code[]" value="{{ $shapeNoteDetail->product->code }}" readonly>
                </div>
                <div class="col-md-10 no-padding">
                    <select type="text" class="form-control" name="product_id[]" style="width:100%" id="product_id" required>
                        <option value="{{ $shapeNoteDetail->product_id }}">{{ $shapeNoteDetail->product->name }}</option>
                    </select>
                </div>
            </td>
            <td data-col-seq="4">
                <input type="number" class="form-control" name="quantity[]" value="{{ $shapeNoteDetail->quantity }}" required>
            </td>
            <td data-col-seq="5">
                <button class="btn btn-primary removeRow hidden"><i class="fa fa-minus" aria-hidden="true"></i></button>
            </td>
        </tr>
    @endforeach
@endsection
