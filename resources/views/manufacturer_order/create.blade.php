@extends('manufacturer_order._form')

@section('action', 'Tạo lệnh sản xuất')

@section('route')
    {{ route('manufacturer-order.store', $contract) }}
@endsection

@section('table-body')

    @foreach ($contract->contract_details as $contract_detail)
        @php($i = $loop->index)
        <tr data-key="{{ $loop->index }}">
            <td class="" data-col-seq="0">
                {{ $loop->iteration }}
                <input type="hidden" value="{{ $contract_detail->id }}" name="contract_detail[{{ $i }}][id]">
            </td>
            <td data-col-seq="1">
                {{ $contract_detail->price->product->code }}
            </td>
            <td data-col-seq="1">

                {{ $contract_detail->price->product->name }}
            </td>
            <td data-col-seq="2">
                {{ $contract_detail->quantity }}
            </td>
            <td data-col-seq="3">
                <div class="pull-right">
                    {{ $contract_detail->deadline }}
                </div>
            </td>
            <td data-col-seq="4">
                {{ $contract_detail->note }}
            </td>
            <td data-col-seq="5">
                <select name="contract_details[{{ $i }}][supplier_id]" class="form-control" required>
                    <option value="">--Nhập đơn vị sản xuất--</option>
                    @foreach( $suppliers as $supplier )
                        <option value="{{ $supplier->id }}">{{ $supplier->short_name }}</option>
                    @endforeach
                </select>
            </td>
            <td </td>
        </tr>
    @endforeach
@endsection
