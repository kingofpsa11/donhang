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
    @foreach( $manufacturerOrder->manufacturerOrderDetails as $manufacturerOrderDetail )
        @php( $i = $loop->index )
        <tr data-key="{{ $i }}">
            <td data-col-seq="0">{{ $loop->iteration }}</td>
            <td data-col-seq="1">
                {{ $manufacturerOrderDetail->contractDetail->price->product->name }}
            </td>
            <td data-col-seq="1">
                <input type="hidden" name="manufacturerNoteDetails[{{ $i }}][contract_detail_id]" value="{{ $manufacturerOrderDetail->contract_detail_id }}">
                <select type="text" class="form-control contract_detail" name="manufacturerNoteDetails[{{ $i }}][contract_detail_id]" style="width:100%" required>
                    @foreach ($manufacturerOrderDetail->contractDetail->price->product->boms as $bom)
                        <option value="{{ $bom->id }}">{{ $bom->name }}</option>
                    @endforeach
                </select>
            </td>
            <td data-col-seq="3">
                <select type="text" class="form-control bom" name="manufacturerNoteDetails[{{ $i }}][bom_id]" style="width:100%" required>
                    <option value="">--Chọn định mức của sản phẩm--</option>
                </select>
            </td>
            <td data-col-seq="4">
                <input type="number" class="form-control" name="manufacturerNoteDetails[{{ $i }}][quantity]" required>
            </td>
            <td data-col-seq="5">
                <input type="text" class="form-control" name="manufacturerNoteDetails[{{ $i }}][note]">
            </td>
        </tr>
    @endforeach
@endsection

@section('javascript')
    @parent
        });
    </script>
@stop
