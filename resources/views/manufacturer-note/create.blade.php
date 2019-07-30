@extends('manufacturer-note._form')

@section('action', 'Tạo phiếu sản xuất')

@section('route')
    {{ route('manufacturer-notes.store') }}
@endsection

@section('table-body')
    <tr data-key="0">
        <td data-col-seq="0">
            <span>1</span>
        </td>
        <td data-col-seq="1">
            <select name="contract_detail_id[]" class="form-control" style="width: 100%">
                <option value="" hidden>Chọn loại phôi</option>
                @foreach( $manufacturerOrder->contract->contractDetails as $contractDetail)
                    @foreach( $contractDetail->price->product->boms as $bom )
                        <optgroup label="{{ $contractDetail->price->product->name }}">
                        @foreach( $bom->bomDetails as $bomDetail )
                            <option value="{{ $contractDetail->id }}" data-bom-detail-id="{{ $bomDetail->id }}" data-product-id="{{ $bomDetail->product->id }}">{{ $bomDetail->product->name }}</option>
                        @endforeach
                        </optgroup>
                    @endforeach
                @endforeach
            </select>
            <input type="hidden" name="bom_detail_id[]">
        </td>
        <td data-col-seq="2">
            <select class="form-control" name="product_id[]" style="width: 100%" required>
            </select>
        </td>
        <td data-col-seq="3">
            <input type="text" class="form-control" name="quantity[]" required>
        </td>
        <td data-col-seq="4">
            <input type="text" class="form-control" name="note[]">
        </td>
        <td data-col-seq="5">
            <button class="btn btn-primary removeRow hidden"><i class="fa fa-minus" aria-hidden="true"></i></button>
        </td>
    </tr>
@endsection