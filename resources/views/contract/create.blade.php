@extends('contract._form')

@section('route')
    {{ route('contracts.store') }}
@endsection

@section('table-body')
    <tr data-key="0">
        <td data-col-seq="0">1</td>
        <td data-col-seq="1">
            <input type="text" class="form-control" name="code[]" readonly>
        </td>
        <td data-col-seq="2">
            <select class="form-control select2 price" style="width: 100%;" name="price_id[]" required>
            </select>
        </td>
        <td data-col-seq="3">
            <input type="number" class="form-control" name="quantity[]" required>
        </td>
        <td data-col-seq="4">
            <input type="text" class="form-control" name="selling_price[]" readonly>
        </td>
        <td data-col-seq="5">
            <div class="input-group">
                <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                </div>
                <input type="text" class="form-control" data-inputmask="'alias': 'dd/mm/yyyy'" data-mask name="deadline[]" required>
            </div>
        </td>
        <td data-col-seq="6">
            <select name="supplier_id[]" class="form-control">
                <option hidden>Lựa chọn nhà cung cấp</option>
                @foreach($suppliers as $supplier)
                    <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                @endforeach
            </select>
        </td>
        <td data-col-seq="7">
            <input type="text" class="form-control" name="note[]">
        </td>
        <td data-col-seq="8">
            <button class="btn btn-primary removeRow hidden"><i class="fa fa-minus" aria-hidden="true"></i></button>
        </td>
    </tr>

@endsection
