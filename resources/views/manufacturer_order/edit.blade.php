@extends('contract.form')

@section('route')
    {{ route('contract.update', $contract->id) }}
@endsection

@section('contract-date')
    {{ $contract->date }}
@stop

@section('contract-number')
    <div class="col-md-3">
        <div class="form-group">
            <label>Số đơn hàng</label>
            <input type="text" class="form-control" placeholder="Nhập số đơn hàng ..." name="contract[number]" value="{{ $contract->number }}">
        </div>
    </div>
    <!-- /.col -->
@stop

@section('contract-total-value')
    {{ $contract->total_value }}
@stop

@section('method')
    @method('PUT')
@stop

@section('customer')
    @foreach ($customers as $customer)
        <option value="{{ $customer->id }}">{{ $customer->short_name }}</option>
        {{--@if ($customer === $contract->$customer)--}}
            {{--<option value="{{ $customer->id }}" selected>{{ $customer->short_name }}</option>--}}
        {{--@else--}}
            {{--<option value="{{ $customer->id }}">{{ $customer->short_name }}</option>--}}
        {{--@endif--}}
    @endforeach
@stop

@section('table-body')
    @php
        $count = count($contract->contract_details);
        $i = 0;
    @endphp
    @foreach ($contract->contract_details as $contract_detail)
        <tr data-key="{{ $i }}">
            <td data-col-seq="0">{{ $i + 1 }}</td>
            <td class="col-md-4" data-col-seq="1">
                <div class="form-group">
                    <select class="form-control input-sm select2 price" style="width: 100%;" name="contract_detail[{{ $i }}][price_id]">
                        <option value="{{ $contract_detail->price_id }}">{{ $contract_detail->price->product->name }}</option>
                    </select>
                </div>
            </td>
            <td class="col-md-1" data-col-seq="2">
                <div class="form-group">
                    <input type="number" class="form-control input-sm" name="contract_detail[{{ $i }}][quantity]" value="{{ $contract_detail->quantity }}">
                </div>
            </td>
            <td class="col-md-2" data-col-seq="3">
                <div class="form-group">
                    <input type="text" class="form-control input-sm" name="contract_detail[{{ $i }}][selling_price]" value="{{ $contract_detail->selling_price }}" readonly>
                </div>
            </td>
            <td class="col-md-2" data-col-seq="4">
                <div class="form-group">
                    <div class="input-group">
                        <div class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                        </div>
                        <input type="text" class="form-control input-sm" name="contract_detail[{{ $i }}][deadline]" value="{{ $contract_detail->deadline }}">
                    </div>
                </div>
            </td>
            <td class="col-md-2" data-col-seq="5">
                <div class="form-group">
                    <input type="text" class="form-control input-sm" name="contract_detail[{{ $i }}][note]" value="{{ $contract_detail->note }}">
                </div>
            </td>
            <td data-col-seq="6">
                <button class="btn btn-primary removeRow"><i class="fa fa-minus" aria-hidden="true"></i></button>
            </td>
        </tr>
        @php( $i++ )
    @endforeach
@endsection