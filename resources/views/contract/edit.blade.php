@extends('contract._form')

@section('route')
    {{ route('contract.update', $contract->id) }}
@endsection

@section('contract-date')
    {{ $contract->date }}
@stop

@section('contract-total-value')
    {{ $contract->total_value }}
@stop

@section('method')
    @method('PUT')
@stop

@section('table-body')
    @foreach ($contract->contract_details as $contract_detail)
        @php($i = $loop->index)
        <tr data-key="{{ $i }}">
            <td data-col-seq="0">
                <span>{{ $loop->iteration }}</span>
                <input type="hidden" name="contract_detail[{{ $i }}][id]" value="{{ $contract_detail->id }}">
            </td>
            <td data-col-seq="1">
                <input type="text" class="form-control" name="contract_detail[0][code]" readonly value="{{ $contract_detail->price->product->code }}">
            </td>
            <td data-col-seq="2">
                <div class="form-group">
                    <select class="form-control select2 price" style="width: 100%;" name="contract_detail[{{ $i }}][price_id]" required>
                        <option value="{{ $contract_detail->price_id }}">{{ $contract_detail->price->product->name }}</option>
                    </select>
                </div>
            </td>
            <td data-col-seq="3">
                <div class="form-group">
                    <input type="number" class="form-control" name="contract_detail[{{ $i }}][quantity]" value="{{ $contract_detail->quantity }}" required>
                </div>
            </td>
            <td data-col-seq="4">
                <div class="form-group">
                    <input type="text" class="form-control" name="contract_detail[{{ $i }}][selling_price]" value="{{ $contract_detail->selling_price }}" readonly>
                </div>
            </td>
            <td data-col-seq="5">
                <div class="form-group">
                    <div class="input-group">
                        <div class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                        </div>
                        <input type="text" class="form-control" name="contract_detail[{{ $i }}][deadline]" value="{{ $contract_detail->deadline }}" required>
                    </div>
                </div>
            </td>
            <td data-col-seq="6">
                <div class="form-group">
                    <input type="text" class="form-control" name="contract_detail[{{ $i }}][note]" value="{{ $contract_detail->note }}">
                </div>
            </td>
            <td data-col-seq="7">
                <button class="btn btn-primary removeRow"><i class="fa fa-minus" aria-hidden="true"></i></button>
            </td>
        </tr>
        @php( $i++ )
    @endforeach
@endsection