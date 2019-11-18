@extends('contract._form')

@section('route')
    {{ route('contracts.update', $contract->id) }}
@endsection

@section('method')
    @method('PUT')
@stop

@section('table-body')
    @foreach ($contract->contractDetails as $contractDetail)
        <tr data-key="{{ $loop->index }}">
            <td data-col-seq="0">
                <span>{{ $loop->iteration }}</span>
                <input type="hidden" name="details[0][id]" value="{{ $contractDetail->id }}">
            </td>
            <td data-col-seq="1">
                {{ $contractDetail->price->product->code }}
            </td>
            <td data-col-seq="2">
                <select class="form-control select2 price" style="width: 100%;" name="details[0][price_id]" required>
                    <option value="{{ $contractDetail->price_id }}">{{ $contractDetail->price->product->name }}</option>
                </select>
            </td>
            <td data-col-seq="3">
                <input type="text" class="form-control" name="details[0][quantity]" value="{{ $contractDetail->quantity }}" required>
            </td>
            <td data-col-seq="4">
                <input type="text" class="form-control" name="details[0][selling_price]" value="{{ $contractDetail->selling_price }}" readonly>
            </td>
            <td data-col-seq="5">
                <div class="input-group">
                    <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                    </div>
                    <input type="text" class="form-control" name="details[0][deadline]" value="{{ $contractDetail->deadline }}" required>
                </div>
            </td>
            <td data-col-seq="6">
                <select name="details[0][supplier_id]" class="form-control">
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
                <input type="text" class="form-control" name="details[0][note]" value="{{ $contractDetail->note }}">
            </td>
            <td data-col-seq="8">
                <button class="btn btn-primary removeRow"><i class="fa fa-minus" aria-hidden="true"></i></button>
            </td>
        </tr>
    @endforeach
@endsection