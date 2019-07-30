@extends('good-receive._form')

@section('action', 'Sửa phiếu nhập')

@section('route')
    {{ route('good-receive.update', $goodReceive) }}
@endsection

@section('method')
    @method('PATCH')
@stop

@section('table-body')
    @foreach ($goodReceive->goodReceiveDetails as $goodReceiveDetail)
        <tr data-key="{{ $loop->index }}">
            <td class="" data-col-seq="0">
                <span>{{ $loop->iteration }}</span>
                <input type="hidden" name="good_receive_detail_id[]" value="{{ $goodReceiveDetail->id }}">
            </td>
            <td data-col-seq="1">
                <input type="text" class="form-control" readonly name="code[]" value="{{ $goodReceiveDetail->product->code }}">
            </td>
            <td data-col-seq="2">
                <select class="form-control product_id" style="width: 100%;" name="product_id[]" required>
                    <option value="{{ $goodReceiveDetail->product_id }}">{{ $goodReceiveDetail->product->name }}</option>
                </select>
            </td>
            <td data-col-seq="3">
                <input type="text" class="form-control" name="unit[]" value="{{ $goodReceiveDetail->unit ?? '' }}" readonly>
            </td>
            @role(4)
            <td data-col-seq="4">
                <select class="form-control bom_id" style="width: 100%;" name="bom_id[]">
                    <option value="">Không dùng định mức</option>
                    @foreach($goodReceiveDetail->product->boms as $bom)
                        @if ($bom->id === $goodReceiveDetail->bom_id)
                            <option value="{{ $goodReceiveDetail->bom_id }}" selected>{{ $goodReceiveDetail->bom->name ?? '' }}</option>
                        @else
                            <option value="{{ $bom->id }}">{{ $bom->name }}</option>
                        @endif
                    @endforeach
                </select>
            </td>
            @endrole
            <td data-col-seq="5">
                <select class="form-control" style="width: 100%;" name="store_id[]" required>
                    <option value="{{ $goodReceiveDetail->store_id }}">{{ $goodReceiveDetail->store->code }}</option>
                </select>
            </td>
            <td data-col-seq="6">
                <input type="text" class="form-control" name="quantity[]" value="{{ $goodReceiveDetail->quantity }}" required>
            </td>
            <td data-col-seq="7">
                <button class="btn btn-primary removeRow hidden"><i class="fa fa-minus" aria-hidden="true"></i></button>
            </td>
        </tr>
    @endforeach
@endsection