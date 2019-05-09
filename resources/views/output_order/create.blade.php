@extends('output_order._form')

@section('action', 'Tạo lệnh xuất hàng')

@section('route')
    {{ route('output-order.store') }}
@endsection

@section('output-order-date')
    @php
        echo date('d/m/Y');
    @endphp
@stop
@section('customer')
    <option value="">--Lựa chọn đơn vị đặt hàng--</option>
    @foreach ($customers as $customer)
        <option value="{{ $customer->id }}">{{ $customer->short_name }}</option>
    @endforeach
@stop
@section('table-body')
    <tr data-key="0">
        <td data-col-seq="0">1</td>
        <td class="col-md-1" data-col-seq="1">
            <input class="form-control input-sm" name="outputOrderDetails[0][contract_id]" readonly>
        </td>
        <td class="col-md-1" data-col-seq="2">
            <input type="text" class="form-control input-sm" name="outputOrderDetails[0][manufacturer_order_number]" readonly>
        </td>
        <td class="col-md-2" data-col-seq="3">
            <input type="text" class="form-control input-sm" name="outputOrderDetails[0][code]" readonly>
        </td>
        <td class="col-md-5" data-col-seq="4">
            <select type="text" class="form-control input-sm select2 contract" name="outputOrderDetails[0][contract_detail_id]" style="width:100%" required>
            </select>
        </td>
        <td class="col-md-1" data-col-seq="5">
            <input type="number" class="form-control input-sm" name="outputOrderDetails[0][quantity]">
        </td>
        <td class="col-md-2" data-col-seq="6">
            <input type="text" class="form-control input-sm" name="outputOrderDetails[0][note]">
        </td>
        <td data-col-seq="7">
            <button class="btn btn-primary removeRow hidden"><i class="fa fa-minus" aria-hidden="true"></i></button>
        </td>
    </tr>
@endsection

@section('javascript')
    @parent
        });
    </script>
@stop
