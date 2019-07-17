@extends('contract._form')

@section('route')
    {{ route('contract.store') }}
@endsection

@section('table-body')
    <tr data-key="0">
        <td data-col-seq="0">1</td>
        <td data-col-seq="1">
            <input type="text" class="form-control" name="contract_details[0][code]" readonly>
        </td>
        <td data-col-seq="2">
            <select class="form-control select2 price" style="width: 100%;" name="contract_details[0][price_id]" required>
            </select>
        </td>
        <td data-col-seq="3">
            <input type="number" class="form-control" name="contract_details[0][quantity]" required>
        </td>
        <td data-col-seq="4">
            <input type="text" class="form-control" name="contract_details[0][selling_price]" readonly>
        </td>
        <td data-col-seq="5">
            <div class="input-group">
                <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                </div>
                <input type="text" class="form-control" data-inputmask="'alias': 'dd/mm/yyyy'" data-mask name="contract_details[0][deadline]" required>
            </div>
        </td>
        <td data-col-seq="6">
            <select name="contract_details[0][supplier_id]" class="form-control">
                @foreach($suppliers as $supplier)
                    <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                @endforeach
            </select>
        </td>
        <td data-col-seq="7">
            <input type="text" class="form-control" name="contract_details[0][note]">
        </td>
        <td data-col-seq="8">
            <button class="btn btn-primary removeRow hidden"><i class="fa fa-minus" aria-hidden="true"></i></button>
        </td>
    </tr>

@endsection
