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
        @php($i = $loop->index)
        <tr data-key="{{ $i }}">
            <td data-col-seq="0">
                <span>{{ $loop->iteration }}</span>
                <input type="hidden" name="details[{{ $i }}][id]" value="{{ $manufacturerNoteDetail->id }}">
            </td>
            <td data-col-seq="1">
                <p class="manufacturer-order-number">{{ $manufacturerNoteDetail->contractDetail->manufacturerOrderDetail->manufacturerOrder->number }}</p>
            </td>
            <td data-col-seq="2">
                <select name="details[{{ $i }}][contract_detail_id]" class="form-control" style="width: 100%">
                    <option value="{{ $manufacturerNoteDetail->contract_detail_id }}">{{ $manufacturerNoteDetail->contractDetail->price->product->name }}</option>
                </select>
                <select class="form-control" name="details[{{ $i }}][product_id]" style="width: 100%" required>
                    <option value="{{ $manufacturerNoteDetail->product_id }}">{{ $manufacturerNoteDetail->product->name }}</option>
                </select>
                <input type="hidden" class="bom-id" name="details[{{ $i }}][bom_id]" value="{{ $manufacturerNoteDetail->bom_id }}">
                <input type="hidden" class="bom-detail-quantity" name="details[{{ $i }}][bom_detail_quantity]" value="{{ $manufacturerNoteDetail->bom_detail_quantity }}">
            </td>
            <td data-col-seq="3">
                <input type="text" class="form-control" name="details[{{ $i }}][length]" value="{{ $manufacturerNoteDetail->length }}" required>
            </td>
            <td data-col-seq="3">
                <input type="text" class="form-control" name="details[{{ $i }}][thickness]" value="{{ $manufacturerNoteDetail->thickness }}" required>
            </td>
            <td data-col-seq="3">
                <input type="text" class="form-control" name="details[{{ $i }}][top_perimeter]" value="{{ $manufacturerNoteDetail->top_perimeter }}" required>
            </td>
            <td data-col-seq="3">
                <input type="text" class="form-control" name="details[{{ $i }}][bottom_perimeter]" value="{{ $manufacturerNoteDetail->bottom_perimeter }}" required>
            </td>
            <td data-col-seq="3">
                <div class="form-group @error('details.'.$i.'.quantity') has-error @enderror">
                    <input type="text" class="form-control" name="details[{{ $i }}][quantity]" value="{{ $manufacturerNoteDetail->quantity }}" required>
                    @error('details.'.$i.'.quantity')
                    <span class="help-block text-sm">{{ $message }}</span>
                    @enderror
                </div>
            </td>
            <td data-col-seq="4">
                <input type="text" class="form-control" name="details[{{ $i }}][note]" value="{{ $manufacturerNoteDetail->note }}">
            </td>
            <td data-col-seq="5">
                <button class="btn btn-primary removeRow hidden"><i class="fa fa-minus" aria-hidden="true"></i></button>
            </td>
        </tr>
    @endforeach
@endsection