@extends('good-deliveries._form')

@section('action', 'Tạo phiếu')

@section('route')
    {{ route('good-delivery.store', [$outputOrder]) }}
@endsection

@section('date')
   {{ date('d/m/Y') }}
@stop

@section('table-body')
    @foreach ($outputOrder->outputOrderDetails as $outputOrderDetail)
        @php( $i = $loop->index)
        <tr data-key="{{ $loop->index }}">
            <td class="" data-col-seq="0">{{ $loop->iteration }}</td>
            <td class="col-md-6" data-col-seq="1">
                <input type="hidden" value="{{ $outputOrderDetail->id }}" name="goodDeliveryDetails[{{ $i }}][output_order_detail_id]">
                {{ $outputOrderDetail->contractDetail->price->product->name }}
            </td>
            <td class="col-md-2" data-col-seq="2">
                {{ $outputOrderDetail->quantity }}
            </td>
            <td class="col-md-2" data-col-seq="3">
                <input type="text" class="form-control" name="goodDeliveryDetails[{{ $i }}][quantity]" required>
            </td>
            <td class="col-md-2" data-col-seq="4">
                {{ $outputOrderDetail->note }}
            </td>
        </tr>
    @endforeach
@endsection
