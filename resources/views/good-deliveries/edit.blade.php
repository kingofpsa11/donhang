@extends('good-deliveries._form', ['view' => $view])

@section('action', 'Sửa phiếu xuất')

@section('route')
    {{ route('good-deliveries.update', $goodDelivery) }}
@endsection

@section('method')
    @method('PATCH')
@stop

@section('table-body')
    @php
        if(isset($goodDelivery->outputOrder)) {
            $view = 'readonly';
        } else {
            $view = 'required';
        }
    @endphp
    @foreach ($goodDelivery->goodDeliveryDetails as $goodDeliveryDetail)
        <tr data-key="{{ $loop->index }}">
            <td class="" data-col-seq="0">
                <span>{{ $loop->iteration }}</span>
                <input type="hidden" name="good_delivery_detail_id[]" value="{{ $goodDeliveryDetail->id }}">
            </td>
            <td data-col-seq="1">
                <input type="text" class="form-control" name="code[]" value="{{ $goodDeliveryDetail->product->code }}" readonly>
            </td>
            <td data-col-seq="2">
                <select class="form-control product_id" style="width: 100%;" name="product_id[]" @if($view === 'readonly') disabled @endif>
                    <option value="{{ $goodDeliveryDetail->product_id }}">{{ $goodDeliveryDetail->product->name }}</option>
                </select>
            </td>
            <td data-col-seq="3">
                <input type="text" class="form-control" name="unit[]" value="{{ $goodDeliveryDetail->product->unit ?? '' }}" readonly>
            </td>
            <td data-col-seq="4">
                <select class="form-control" style="width: 100%;" name="store_id[]" required>
                    @if (isset($goodDeliveryDetail->store))
                        <option value="{{ $goodDeliveryDetail->store_id }}">{{ $goodDeliveryDetail->store->code }}</option>
                    @endif
                </select>
            </td>
            <td data-col-seq="5">
                <input type="text" class="form-control" name="quantity[]" value="{{ $goodDeliveryDetail->quantity }}" readonly>
            </td>
            <td data-col-seq="6">
                <input type="number" class="form-control" name="actual_quantity[]" value="{{ $goodDeliveryDetail->actual_quantity }}" required>
            </td>
            <td data-col-seq="7">
                <button class="btn btn-primary removeRow hidden"><i class="fa fa-minus" aria-hidden="true"></i></button>
            </td>
        </tr>
    @endforeach
@endsection