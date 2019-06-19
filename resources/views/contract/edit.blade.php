@extends('contract._form')

@section('route')
    {{ route('contract.update', $contract->id) }}
@endsection

@section('method')
    @method('PUT')
@stop

@section('table-body')
    @foreach ($contract->contract_details as $contract_detail)
        @php($i = $loop->index)
        <tr data-key="{{ $i }}">
            <td data-col-seq="0">
                <span>{{ $loop->iteration }}</span>
                <input type="hidden" name="contract_details[{{ $i }}][id]" value="{{ $contract_detail->id }}">
            </td>
            <td data-col-seq="1">
                <input type="text" class="form-control" name="contract_details[{{ $i }}][code]" readonly value="{{ $contract_detail->price->product->code }}">
            </td>
            <td data-col-seq="2">
                <select class="form-control select2 price" style="width: 100%;" name="contract_details[{{ $i }}][price_id]" required>
                    <option value="{{ $contract_detail->price_id }}">{{ $contract_detail->price->product->name }}</option>
                </select>
            </td>
            <td data-col-seq="3">
                <input type="number" class="form-control" name="contract_details[{{ $i }}][quantity]" value="{{ $contract_detail->quantity }}" required>
            </td>
            <td data-col-seq="4">
                <input type="text" class="form-control" name="contract_details[{{ $i }}][selling_price]" value="{{ $contract_detail->selling_price }}" readonly>
            </td>
            <td data-col-seq="5">
                <div class="input-group">
                    <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                    </div>
                    <input type="text" class="form-control" name="contract_details[{{ $i }}][deadline]" value="{{ $contract_detail->deadline }}" required>
                </div>
            </td>
            <td data-col-seq="6">
                <input type="text" class="form-control" name="contract_details[{{ $i }}][note]" value="{{ $contract_detail->note }}">
            </td>
            <td data-col-seq="7">
                <button class="btn btn-primary removeRow"><i class="fa fa-minus" aria-hidden="true"></i></button>
            </td>
        </tr>
        @php( $i++ )
    @endforeach
@endsection