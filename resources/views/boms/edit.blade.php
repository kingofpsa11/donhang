@extends('boms._form')

@section('route')
    {{ route('bom.update', $bom) }}
@endsection

@section('method')
    @method('PUT')
@stop

@section('table-body')
    @php ($i = 0)
    @foreach ($bom->bomDetails as $bomDetail)
        <tr data-key="{{ $i }}">
            <td data-col-seq="0">{{ $i + 1 }}</td>
            <td class="col-md-1" data-col-seq="1">
                <input type="text" class="form-control" value="{{ $bomDetail->product->code }}" readonly>
            </td>
            <td class="col-md-6" data-col-seq="2">
                <select class="form-control input-sm select2 price" style="width: 100%;" name="$bomDetail[{{ $i }}][product_id]">
                    <option value="{{ $bomDetail->product_id }}">{{ $bomDetail->product->name }}</option>
                </select>
            </td>
            <td class="col-md-2" data-col-seq="3">
                <input type="text" class="form-control input-sm" name="$bomDetails[{{ $i }}][quantity]" value="{{ $bomDetail->quantity }}">
            </td>
            <td class="col-md-3" data-col-seq="4">
                <input type="text" class="form-control input-sm" name="$bomDetails[{{ $i }}][note]">
            </td>
            <td data-col-seq="5">
                <button class="btn btn-primary removeRow hidden"><i class="fa fa-minus" aria-hidden="true"></i></button>
            </td>
        </tr>
        @php( $i++ )
    @endforeach
@endsection