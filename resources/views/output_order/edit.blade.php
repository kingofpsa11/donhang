@extends('output_order._form')

@section('action', 'Sửa lênh xuất hàng')

@section('route')
    {{ route('output-orders.update', $outputOrder) }}
@endsection

@section('method')
    @method('PATCH')
@stop

@section('table-body')
    @foreach ($outputOrder->outputOrderDetails as $outputOrderDetail)
        <tr data-key="{{ $loop->index }}">
            <td data-col-seq="0">
                <span>{{ $loop->iteration }}</span>
                <input type="hidden" name="output_order_detail_id[]" value="{{ $outputOrderDetail->id }}">
            </td>
            <td class="col-md-1" data-col-seq="1">
                <input class="form-control" name="contract_id[]" readonly value="{{ $outputOrderDetail->contractDetail->contract->number }}">
            </td>
            <td class="col-md-1" data-col-seq="2">
                <input type="text" class="form-control" name="manufacturer_order_number[]" readonly value="{{ $outputOrderDetail->contractDetail->manufacturerOrder->number ?? ''}}">
            </td>
            <td class="col-md-2" data-col-seq="3">
                <input type="text" class="form-control" name="code[]" readonly value="{{ $outputOrderDetail->contractDetail->price->product->code }}">
            </td>
            <td class="col-md-5" data-col-seq="4">
                <select type="text" class="form-control select2 contract" name="contract_detail_id[]" style="width:100%" required>
                    <option value="{{ $outputOrderDetail->contract_detail_id }}">{{ $outputOrderDetail->contractDetail->price->product->name }}</option>
                </select>
            </td>
            <td class="col-md-1" data-col-seq="5">
                <input type="number" class="form-control" name="quantity[]" value="{{ $outputOrderDetail->quantity }}">
            </td>
            <td class="col-md-2" data-col-seq="6">
                <input type="text" class="form-control" name="note[]" value="{{ $outputOrderDetail->note }}">
            </td>
            <td data-col-seq="7">
                <button class="btn btn-primary removeRow"><i class="fa fa-minus" aria-hidden="true"></i></button>
            </td>
        </tr>
    @endforeach
@endsection
