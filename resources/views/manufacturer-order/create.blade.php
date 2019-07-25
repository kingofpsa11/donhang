@extends('manufacturer-order._form')

@section('action', 'Tạo lệnh sản xuất')

@section('route')
    {{ route('manufacturer-order.store', $contract) }}
@endsection

@section('table-body')

    @foreach ($contract->contractDetails as $contractDetail)
        @php($i = $loop->index)
        <tr data-key="{{ $loop->index }}">
            <td class="" data-col-seq="0">
                {{ $loop->iteration }}
                <input type="hidden" value="{{ $contractDetail->id }}" name="contract_detail[{{ $i }}][id]">
            </td>
            <td data-col-seq="1">
                {{ $contractDetail->price->product->code }}
            </td>
            <td data-col-seq="1">

                {{ $contractDetail->price->product->name }}
            </td>
            <td data-col-seq="2">
                {{ $contractDetail->quantity }}
            </td>
            <td data-col-seq="3">
                <div class="pull-right">
                    {{ $contractDetail->deadline }}
                </div>
            </td>
            <td data-col-seq="4">
                {{ $contractDetail->note }}
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
