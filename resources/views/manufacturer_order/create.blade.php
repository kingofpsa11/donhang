@extends('manufacturer_order.form')

@section('route')
    {{ route('manufacturer-order.store', $contract) }}
@endsection

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
            <td class="col-md-2" data-col-seq="3">
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
                <select name="contract_detail[{{ $i }}][supplier_id]" class="form-control">
                    <option value="">--Nhập đơn vị sản xuất--</option>
                    @foreach($suppliers as $supplier)
                        <option value="{{ $supplier->id }}">{{ $supplier->short_name }}</option>
                    @endforeach
                </select>
            </td>
        </tr>
        @php($i++)
    @endforeach
@endsection