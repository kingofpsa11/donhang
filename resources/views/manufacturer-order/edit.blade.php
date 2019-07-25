@extends('manufacturer-order._form')

@section('action', 'Sửa lệnh sản xuất')

@section('route')
    {{ route('manufacturer-order.update', $contract) }}
@endsection

@section('contract-date')
    {{ $contract->date }}
@stop

@section('method')
    @method('PUT')
@stop

@section('table-body')
    @php($i=0)
    @foreach ($contract->contract_details as $contract_detail)
        <tr data-key="{{ $i }}">
            <td class="" data-col-seq="0">{{ $i + 1 }}</td>
            <td class="col-md-4" data-col-seq="1">
                <input type="hidden" value="{{ $contract_detail->id }}" name="contract_detail[{{ $i }}][id]">
                {{ $contract_detail->price->product->name }}
            </td>
            <td class="col-md-1" data-col-seq="2">
                {{ $contract_detail->quantity }}
            </td>
            <td class="col-md-1" data-col-seq="3">
                <div class="pull-right">
                    {{ $contract_detail->deadline }}
                </div>
            </td>
            <td class="col-md-1" data-col-seq="4">
                {{ $contract_detail->status }}
            </td>
            <td class="col-md-2" data-col-seq="5">
                {{ $contract_detail->note }}
            </td>
            <td class="col-md-2" data-col-seq="6">
                <select name="contract_detail[{{ $i }}][supplier_id]" class="form-control" required>
                    <option value="">--Nhập đơn vị sản xuất--</option>
                    @foreach($suppliers as $supplier)
                        @if ($supplier->id === $contract_detail->manufacturerOrder->supplier_id)
                            <option value="{{ $supplier->id }}" selected>{{ $supplier->short_name }}</option>
                        @else
                            <option value="{{ $supplier->id }}">{{ $supplier->short_name }}</option>
                        @endif
                    @endforeach
                </select>
            </td>
            <td class="col-md-1" data-col-seq="6">
                {{ $contract_detail->manufacturerOrder->number }}
            </td>
        </tr>
        @php($i++)
    @endforeach
@endsection