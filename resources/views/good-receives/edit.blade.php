@extends('good-receives._form')

@section('action', 'Sửa phiếu nhập')

@section('route')
    {{ route('good-receive.update', $goodReceive) }}
@endsection

@section('method')
    @method('PUT')
@stop

@section('table-body')
    @foreach ($goodReceive->goodReceiveDetails as $goodReceiveDetail)
        @php( $i = $loop->index)
        <tr data-key="{{ $loop->index }}">
            <td class="" data-col-seq="0">
                <span>{{ $loop->iteration }}</span>
                <input type="hidden" name="goodReceiveDetails[{{ $i }}][id]" value="{{ $goodReceiveDetail->id }}">
            </td>
            <td data-col-seq="1">
                <input type="text" class="form-control" readonly name="goodReceiveDetails[{{ $i }}][code]" value="{{ $goodReceiveDetail->product->code }}">
            </td>
            <td data-col-seq="2">
                <select class="form-control product_id" style="width: 100%;" name="goodReceiveDetails[{{ $i }}][product_id]" required>
                    <option value="{{ $goodReceiveDetail->product_id }}">{{ $goodReceiveDetail->product->name }}</option>
                </select>
            </td>
            <td data-col-seq="3">
                <input type="text" class="form-control" name="goodReceiveDetails[0][unit]" readonly>
            </td>
            <td data-col-seq="4">
                <select class="form-control bom_id" style="width: 100%;" name="goodReceiveDetails[0][bom_id]">
                    <option value="">--Không dùng định mức--</option>
                    @foreach($goodReceiveDetail->product->boms as $bom)
                        @if ($bom->id === $goodReceiveDetail->bom_id)
                            <option value="{{ $goodReceiveDetail->bom_id }}" selected>{{ $goodReceiveDetail->bom->name ?? '' }}</option>
                        @else
                            <option value="{{ $bom->id }}">{{ $bom->name }}</option>
                        @endif
                    @endforeach
                </select>
            </td>
            <td data-col-seq="5">
                <input type="text" class="form-control" name="goodReceiveDetails[0][store_id]" value="{{ $goodReceiveDetail->store_id }}" required>
            </td>
            <td data-col-seq="6">
                <input type="text" class="form-control" name="goodReceiveDetails[0][quantity]" value="{{ $goodReceiveDetail->quantity }}" required>
            </td>
            <td data-col-seq="7">
                <input type="text" class="form-control" name="goodReceiveDetails[0][actual_quantity]" value="{{ $goodReceiveDetail->actual_quantity }}">
            </td>
            <td data-col-seq="8">
                <button class="btn btn-primary removeRow hidden"><i class="fa fa-minus" aria-hidden="true"></i></button>
            </td>
        </tr>
    @endforeach
@endsection