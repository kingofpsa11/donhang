@extends('good-deliveries._form')

@section('action', 'Sửa phiếu xuất')

@section('route')
    {{ route('good-delivery.update', $outputOrder) }}
@endsection

@section('method')
    @method('PUT')
@stop

@section('table-body')
    @foreach ($outputOrder->goodDelivery->goodDeliveryDetails as $goodDeliveryDetail)
        @php( $i = $loop->index)
        <tr data-key="{{ $loop->index }}">
            <td class="" data-col-seq="0">{{ $loop->iteration }}</td>
            <td class="col-md-6" data-col-seq="1">
                <input type="hidden" value="{{ $goodDeliveryDetail->outputOrderDetail->id }}" name="goodDeliveryDetails[{{ $i }}][output_order_detail_id]">
                {{ $goodDeliveryDetail->outputOrderDetail->contractDetail->product->name }}
            </td>
            <td class="col-md-2" data-col-seq="2">
                {{ $goodDeliveryDetail->outputOrderDetail->quantity }}
            </td>
            <td class="col-md-2" data-col-seq="3">
                <input type="text" class="form-control" name="goodDeliveryDetails[{{ $i }}][quantity]" required value="{{ $goodDeliveryDetail->quantity }}">
            </td>
            <td class="col-md-2" data-col-seq="4">
                {{ $goodDeliveryDetail->outputOrderDetail->note }}
            </td>
        </tr>
    @endforeach
@endsection