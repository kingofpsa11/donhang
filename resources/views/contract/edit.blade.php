@extends('contract._form')

@section('route')
    {{ route('contracts.update', $contract->id) }}
@endsection

@section('method')
    @method('PUT')
@stop

@section('table-body')
    @foreach ($contract->contractDetails as $contractDetail)
        @php($i = $loop->index)
        <tr data-key="{{ $i }}">
            <td data-col-seq="0">
                <span>{{ $loop->iteration }}</span>
                <input type="hidden" name="contract_detail_id[]" value="{{ $contractDetail->id }}">
            </td>
            <td data-col-seq="1">
                <input type="text" class="form-control" name="code[]" readonly value="{{ $contractDetail->price->product->code }}">
            </td>
            <td data-col-seq="2">
                <select class="form-control select2 price" style="width: 100%;" name="price_id[]" required>
                    <option value="{{ $contractDetail->price_id }}">{{ $contractDetail->price->product->name }}</option>
                </select>
            </td>
            <td data-col-seq="3">
                <input type="number" class="form-control" name="quantity[]" value="{{ $contractDetail->quantity }}" required>
            </td>
            <td data-col-seq="4">
                <input type="text" class="form-control" name="selling_price[]" value="{{ $contractDetail->selling_price }}" readonly>
            </td>
            <td data-col-seq="5">
                <div class="input-group">
                    <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                    </div>
                    <input type="text" class="form-control" name="deadline[]" value="{{ $contractDetail->deadline }}" required>
                </div>
            </td>
            <td data-col-seq="6">
                <select name="supplier_id[]" class="form-control">
                    @foreach($suppliers as $supplier)
                        @if ($contractDetail->supplier_id === $supplier->id)
                            <option value="{{ $supplier->id }}" selected>{{ $supplier->name }}</option>
                        @else
                            <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                        @endif
                    @endforeach
                </select>
            </td>
            <td data-col-seq="7">
                <input type="text" class="form-control" name="note[]" value="{{ $contractDetail->note }}">
            </td>
            <td data-col-seq="8">
                <button class="btn btn-primary removeRow"><i class="fa fa-minus" aria-hidden="true"></i></button>
            </td>
        </tr>
        @php( $i++ )
    @endforeach
@endsection