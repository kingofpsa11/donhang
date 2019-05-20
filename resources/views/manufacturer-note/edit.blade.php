@extends('output_order._form')

@section('action', 'Sửa lênh xuất hàng')

@section('route')
    {{ route('output-order.update', $outputOrder->id) }}
@endsection

@section('output-order-date')
    {{ $outputOrder->date }}
@stop

@section('customer')
    @foreach ($customers as $customer)
        @if ($customer->id === $outputOrder->customer_id)
            <option value="{{ $customer->id }}" selected>{{ $customer->short_name }}</option>
        @else
            <option value="{{ $customer->id }}">{{ $customer->short_name }}</option>
        @endif
    @endforeach
@stop

@section('method')
    @method('PUT')
@stop

@section('table-body')
    @php( $i = 0)
    @foreach ($outputOrder->outputOrderDetails as $outputOrderDetail)
        <tr data-key="{{ $i }}">
            <td data-col-seq="0">1</td>
            <td class="col-md-1" data-col-seq="1">
                <input class="form-control input-sm" name="outputOrderDetails[{{ $i }}][contract_id]" readonly value="{{ $outputOrderDetail->contractDetail->contract->number }}">
            </td>
            <td class="col-md-1" data-col-seq="2">
                <input type="text" class="form-control input-sm" name="outputOrderDetails[{{ $i }}][manufacturer_order_number]" readonly value="{{ $outputOrderDetail->contractDetail->manufacturerOrder->number ?? ''}}">
            </td>
            <td class="col-md-2" data-col-seq="3">
                <input type="text" class="form-control input-sm" name="outputOrderDetails[{{ $i }}][code]" readonly value="{{ $outputOrderDetail->contractDetail->price->product->code }}">
            </td>
            <td class="col-md-5" data-col-seq="4">
                <select type="text" class="form-control input-sm select2 contract" name="outputOrderDetails[{{ $i }}][contract_detail_id]" style="width:100%" required>
                    <option value="{{ $outputOrderDetail->contract_detail_id }}">{{ $outputOrderDetail->contractDetail->price->product->name }}</option>
                </select>
            </td>
            <td class="col-md-1" data-col-seq="5">
                <input type="number" class="form-control input-sm" name="outputOrderDetails[{{ $i }}][quantity]" value="{{ $outputOrderDetail->quantity }}">
            </td>
            <td class="col-md-2" data-col-seq="6">
                <input type="text" class="form-control input-sm" name="outputOrderDetails[{{ $i }}][note]" value="{{ $outputOrderDetail->note }}">
            </td>
            <td data-col-seq="7">
                <button class="btn btn-primary removeRow hidden"><i class="fa fa-minus" aria-hidden="true"></i></button>
            </td>
        </tr>
        @php( $i++ )
    @endforeach
@endsection

@section('javascript')
    @parent
        addSelect2(contractSelect);
        getProduct(contractSelect);
        });
    </script>
@stop