@extends('output_order._form')

@section('route')
    {{ route('output-order.update', $outputOrder->id) }}
@endsection

@section('output-order-date')
    {{ $outputOrder->date }}
@stop

@section('contract-number')
    <div class="col-md-3">
        <div class="form-group">
            <label>Số lệnh xuất hàng</label>
            <input type="text" class="form-control" placeholder="Nhập số đơn hàng ..." name="outputOrder[number]" value="{{ $outputOrder->number }}">
        </div>
    </div>
    <!-- /.col -->
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
                <div class="form-group">
                    <input class="form-control input-sm" name="outputOrderDetails[{{ $i }}][contract_id]" readonly value="{{ $outputOrderDetail->contractDetail->contract->number }}">
                </div>
            </td>
            <td class="col-md-1" data-col-seq="2">
                <div class="form-group">
                    <input type="text" class="form-control input-sm" name="outputOrderDetails[{{ $i }}][manufacturer_order_number]" readonly value="{{ $outputOrderDetail->contractDetail->manufacturer_order_number }}">
            
                </div>
            </td>
            <td class="col-md-2" data-col-seq="3">
                <div class="form-group">
                    <input type="text" class="form-control input-sm" name="outputOrderDetails[{{ $i }}][code]" readonly value="{{ $outputOrderDetail->contractDetail->price->product->code }}">
            
                </div>
            </td>
            <td class="col-md-5" data-col-seq="4">
                <div class="form-group">
                    <select type="text" class="form-control input-sm select2 contract" name="outputOrderDetails[{{ $i }}][contract_detail_id]" style="width:100%" required>
                        <option value="{{ $outputOrderDetail->contract_detail_id }}">{{ $outputOrderDetail->contractDetail->price->product->name }}</option>
                    </select>
                </div>
            </td>
            <td class="col-md-1" data-col-seq="5">
                <div class="form-group">
                    <input type="number" class="form-control input-sm" name="outputOrderDetails[{{ $i }}][quantity]" value="{{ $outputOrderDetail->quantity }}">
                </div>
            </td>
            <td class="col-md-2" data-col-seq="6">
                <div class="form-group">
                    <input type="text" class="form-control input-sm" name="outputOrderDetails[{{ $i }}][note]" value="{{ $outputOrderDetail->note }}">
                </div>
            </td>
            <td data-col-seq="7">
                <button class="btn btn-primary removeRow"><i class="fa fa-minus" aria-hidden="true"></i></button>
            </td>
        </tr>
        @php( $i++ )
    @endforeach
@endsection