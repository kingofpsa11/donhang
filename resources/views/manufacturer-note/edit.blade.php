@extends('manufacturer-note._form')

@section('action', 'Sửa lênh xuất hàng')

@section('route')
    {{ route('manufacturer-notes.update', $manufacturerNote) }}
@endsection

@section('method')
    @method('PUT')
@stop

@section('table-body')
    @foreach ($manufacturerNote->manufacturerNoteDetails as $manufacturerNoteDetail)
        @php( $i = $loop->index )
        <tr data-key="{{ $loop->iteration }}">
            <td data-col-seq="0">
                <span>{{ $loop->iteration }}</span>
                <input type="hidden" name="manufacturerNoteDetails[{{ $i }}][id]" value="{{ $manufacturerNoteDetail->id }}">
            </td>
            <td data-col-seq="1">
                <select name="manufacturerNoteDetails[{{ $i }}][contract_detail_id]" class="form-control">
                    <option value="" hidden>--Chọn sản phẩm--</option>
                    @foreach( $manufacturerNoteDetail->contractDetail->contract->contractDetails as $contractDetail)
                        @foreach( $contractDetail->price->product->boms as $bom )
                            <optgroup label="{{ $contractDetail->price->product->name }}">
                                @foreach( $bom->bomDetails as $bomDetail )
                                    @if ( $manufacturerNoteDetail->bom_detail_id === $bomDetail->id )
                                        <option value="{{ $contractDetail->id }}" data-bom-detail-id="{{ $bomDetail->id }}" data-product-id="{{ $bomDetail->product_id }}" selected>{{ $bomDetail->product->name }}</option>
                                    @else
                                        <option value="{{ $contractDetail->id }}" data-bom-detail-id="{{ $bomDetail->id }}" data-product-id="{{ $bomDetail->product_id }}">{{ $bomDetail->product->name }}</option>
                                    @endif
                                @endforeach
                            </optgroup>
                        @endforeach
                    @endforeach
                </select>
                <input type="hidden" name="manufacturerNoteDetails[{{ $i }}][bom_detail_id]" value="{{ $manufacturerNoteDetail->bom_detail_id }}">
            </td>
            <td data-col-seq="2">
                <select class="form-control" name="manufacturerNoteDetails[{{ $i }}][product_id]" required>
                    <option value="" hidden>--Chọn loại phôi--</option>
                    @foreach( $manufacturerNoteDetail->bomDetail->product->boms as $bom )
                        <optgroup label="{{ $bom->name }}">
                            @foreach( $bom->bomDetails as $bomDetail )
                                @if ( $bomDetail->product_id === $manufacturerNoteDetail->product_id )
                                    <option value="{{ $bomDetail->product_id }}" selected>{{ $bomDetail->product->name }}</option>
                                @else
                                    <option value="{{ $bomDetail->product_id }}">{{ $bomDetail->product->name }}</option>
                                @endif
                            @endforeach
                        </optgroup>
                    @endforeach
                </select>
            </td>
            <td data-col-seq="3">
                <input type="text" class="form-control" name="manufacturerNoteDetails[{{ $i }}][quantity]" value="{{ $manufacturerNoteDetail->quantity }}" required>
            </td>
            <td data-col-seq="4">
                <input type="text" class="form-control" name="manufacturerNoteDetails[{{ $i }}][note]" value="{{ $manufacturerNoteDetail->note }}">
            </td>
            <td data-col-seq="5">
                <button class="btn btn-primary removeRow hidden"><i class="fa fa-minus" aria-hidden="true"></i></button>
            </td>
        </tr>
    @endforeach
@endsection