@extends('manufacturer-note._form')

@section('action', 'Sửa lênh xuất hàng')

@section('route')
    {{ route('manufacturer-note.update', $manufacturerNote) }}
@endsection

@section('method')
    @method('PUT')
@stop

@section('table-body')
    @foreach ($manufacturerNote->manufacturerNoteDetails as $manufacturerNoteDetail)
        @php( $i = $loop->index )
        <tr data-key="{{ $i }}">
            <td data-col-seq="0">{{ $loop->iteration }}</td>
            <td data-col-seq="1">
                <input class="form-control" readonly name="manufacturerNoteDetails[{{ $i }}][manufacturer_order_number]" value="{{ $manufacturerNoteDetail->contractDetail->manufacturerOrder->number }}">
            </td>
            <td data-col-seq="2">
                <input type="hidden" name="manufacturerNoteDetails[{{ $i }}][product_id]">
                <select type="text" class="form-control contract_detail" name="manufacturerNoteDetails[{{ $i }}][contract_detail_id]" style="width:100%" required>
                    <option value="{{ $manufacturerNoteDetail->contract_detail_id }}">{{ $manufacturerNoteDetail->contractDetail->price->product->name }}</option>
                </select>
            </td>
            <td data-col-seq="3">
                <select type="text" class="form-control bom" name="manufacturerNoteDetails[{{ $i }}][bom_id]" style="width:100%" required>
                    <option value="{{ $manufacturerNoteDetail->bom_id }}">{{ $manufacturerNoteDetail->bom->name }}</option>
                </select>
            </td>
            <td data-col-seq="4">
                <input type="number" class="form-control" name="manufacturerNoteDetails[{{ $i }}][quantity]" value="{{ $manufacturerNoteDetail->quantity }}" required>
            </td>
            <td data-col-seq="5">
                <input type="text" class="form-control" name="manufacturerNoteDetails[{{ $i }}][note]" value="{{ $manufacturerNoteDetail->note }}">
            </td>
            <td data-col-seq="6">
                <button class="btn btn-primary removeRow hidden"><i class="fa fa-minus" aria-hidden="true"></i></button>
            </td>
        </tr>
        @php( $i++ )
    @endforeach
@endsection

@section('javascript')
    @parent
        });
    </script>
@stop