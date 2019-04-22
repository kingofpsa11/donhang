@extends('contract.form')

@section('route')
    {{ route('contract.store') }}
@endsection

@section('contract-date')
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
        <td class="" data-col-seq="0">1</td>
        <td class="col-md-4" data-col-seq="1">
            <div class="form-group">
                <select class="form-control input-sm select2 price" style="width: 100%;" name="contract_detail[0][price_id]">
                </select>
            </div>
        </td>
        <td class="col-md-1" data-col-seq="2">
            <div class="form-group">
                <input type="number" class="form-control input-sm" name="contract_detail[0][quantity]">
            </div>
        </td>
        <td class="col-md-2" data-col-seq="3">
            <div class="form-group">
                <input type="hidden" name="contract_detail[0][price_id]">
                <input type="text" class="form-control input-sm" name="contract_detail[0][selling_price]" readonly>
            </div>
        </td>
        <td class="col-md-2" data-col-seq="4">
            <div class="form-group">
                <div class="input-group">
                    <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                    </div>
                    <input type="text" class="form-control input-sm" data-inputmask="'alias': 'dd/mm/yyyy'" data-mask name="contract_detail[0][deadline]">
                </div>
            </div>
        </td>
        <td class="col-md-2" data-col-seq="5">
            <div class="form-group">
                <input type="text" class="form-control input-sm" name="contract_detail[0][note]">
            </div>
        </td>
        <td data-col-seq="6">
            <button class="btn btn-primary removeRow"><i class="fa fa-minus" aria-hidden="true"></i></button>
        </td>
    </tr>

@endsection
