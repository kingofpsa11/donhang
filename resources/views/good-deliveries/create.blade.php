@extends('good-deliveries._form')

@section('action', 'Tạo lệnh sản xuất')

@section('route')
    {{ route('good-delivery.store', [$outputOrder]) }}
@endsection

@section('table-body')
    @foreach ($outputOrder->outputOrderDetails as $outputOrderDetail)
        <tr data-key="{{ $loop->index }}">
            <td class="" data-col-seq="0">{{ $loop->iteration }}</td>
            <td class="col-md-6" data-col-seq="1">
                <input type="hidden" value="{{ $outputOrderDetail->id }}" name="outputOrderDetails[{{ $loop->index }}][id]">
                {{ $outputOrderDetail->contractDetail->product->name }}
            </td>
            <td class="col-md-2" data-col-seq="2">
                {{ $outputOrderDetail->quantity }}
            </td>
            <td class="col-md-2" data-col-seq="3">
                <input type="text" class="form-control" name="goodDeliveryDetails[{{ $loop->index }}][quantity]">
            </td>
            <td class="col-md-2" data-col-seq="4">
                {{ $outputOrderDetail->note }}
            </td>
        </tr>
        @php($i++)
    @endforeach
@endsection
