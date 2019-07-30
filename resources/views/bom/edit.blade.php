@extends('bom._form')

@section('route')
    {{ route('boms.update', $bom) }}
@endsection

@section('method')
    @method('PUT')
@stop

@section('table-body')
    @php ($i = 0)
    @foreach ($bom->bomDetails as $bomDetail)
        <tr data-key="{{ $loop->index }}">
            <td data-col-seq="0">
                <span>{{ $loop->iteration }}</span>
                <input type="hidden" name="bom_detail_id[]" value="{{ $bomDetail->id }}">
            </td>
            <td class="col-md-1" data-col-seq="1">
                <input type="text" name="code[]" class="form-control" value="{{ $bomDetail->product->code }}" readonly>
            </td>
            <td class="col-md-6" data-col-seq="2">
                <select class="form-control select2 price" style="width: 100%;" name="bom_product_id[]">
                    <option value="{{ $bomDetail->product_id }}">{{ $bomDetail->product->name }}</option>
                </select>
            </td>
            <td class="col-md-2" data-col-seq="3">
                <input type="text" class="form-control" name="quantity[]" value="{{ $bomDetail->quantity }}">
            </td>
            <td class="col-md-3" data-col-seq="4">
                <input type="text" class="form-control" name="note[]">
            </td>
            <td data-col-seq="5">
                <button class="btn btn-primary removeRow"><i class="fa fa-minus" aria-hidden="true"></i></button>
            </td>
        </tr>
        @php( $i++ )
    @endforeach
@endsection