@extends('good-deliveries._form', ['view' => $view])

@section('action', 'Sửa phiếu xuất')

@section('route')
    {{ route('good-delivery.update', $goodDelivery) }}
@endsection

@section('method')
    @method('PUT')
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
        @php( $i = $loop->index)
        <tr data-key="{{ $loop->index }}">
            <td class="" data-col-seq="0">
                <span>{{ $loop->iteration }}</span>
                <input type="hidden" name="goodDeliveryDetails[{{ $i }}][id]" value="{{ $goodDeliveryDetail->id }}">
            </td>
            <td data-col-seq="1">
                <input type="text" class="form-control" name="goodDeliveryDetails[{{ $i }}][code]" value="{{ $goodDeliveryDetail->product->code }}" readonly>
            </td>
            <td data-col-seq="2">
                <select class="form-control product_id" style="width: 100%;" name="goodDeliveryDetails[{{ $i }}][product_id]" @if($view === 'readonly') disabled @endif>
                    <option value="{{ $goodDeliveryDetail->product_id }}">{{ $goodDeliveryDetail->product->name }}</option>
                </select>
            </td>
            <td data-col-seq="3">
                <input type="text" class="form-control" name="goodDeliveryDetails[0][unit]" readonly>
            </td>
            <td data-col-seq="4">
                <input type="text" class="form-control" name="goodDeliveryDetails[0][store_id]" value="{{ $goodDeliveryDetail->store_id }}" {{ $view }}>
            </td>
            <td data-col-seq="5">
                <input type="text" class="form-control" name="goodDeliveryDetails[0][quantity]" value="{{ $goodDeliveryDetail->quantity }}" {{ $view }}>
            </td>
            <td data-col-seq="6">
                <input type="text" class="form-control" name="goodDeliveryDetails[0][actual_quantity]" value="{{ $goodDeliveryDetail->actual_quantity }}">
            </td>
            <td data-col-seq="7">
                <button class="btn btn-primary removeRow hidden"><i class="fa fa-minus" aria-hidden="true"></i></button>
            </td>
        </tr>
    @endforeach
@endsection