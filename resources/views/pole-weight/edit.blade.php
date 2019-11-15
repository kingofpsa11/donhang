@extends('pole-weight._form')

@section('route')
    {{ route('pole-weight.update', $poleWeight) }}
@endsection

@section('method')
    @method('PUT')
@stop

@section('table-body')
    @php ($i = 0)
    @foreach ($poleWeight->poleWeightDetails as $detail)
        <tr data-key="0">
            <td data-col-seq="0">
                <span>1</span>
                <input type="hidden" name="details[{{ $loop->index }}][id]" value="{{ $detail->id }}">
            </td>
            <td class="" data-col-seq="2">
                <input type="text" class="form-control" name="details[{{ $loop->index }}][name]" >
            </td>
            <td class="">
                <select name="details[{{ $loop->index }}][shape]" id="" class="form-control">
                    <option value="{{ $detail->shape }}">Hinh dang</option>
                    <option value="0">Thép tấm vuông</option>
                    <option value="1">Thép tấm tròn</option>
                    <option value="2">Ống tròn</option>
                    <option value="3">Ống bát giác</option>
                </select>
            </td>
            <td class="col-md-1" data-col-seq="2">
                <input type="text" class="form-control quantity" name="details[{{ $loop->index }}][quantity]" required value="{{ $detail->quantity }}">
            </td>
            <td class="col-md-1" data-col-seq="2">
                <input type="text" class="form-control decimal" name="details[{{ $loop->index }}][d_ngon]" @if (isset($detail->d_ngon))
                    value="{{ $detail->d_ngon }}"
                @else
                    disabled
                @endif>
            </td>
            <td class="col-md-1" data-col-seq="2">
                <input type="text" class="form-control decimal" name="details[{{ $loop->index }}][d_goc]" @if (isset($detail->d_goc))
                    value="{{ $detail->d_goc }}"
                @else
                    disabled
                @endif>
            </td>
            <td class="col-md-1" data-col-seq="2">
                <input type="text" class="form-control decimal" name="details[{{ $loop->index }}][day]" @if (isset($detail->day))
                    value="{{ $detail->day }}"
                @else
                    disabled
                @endif>
            </td>
            <td class="col-md-1" data-col-seq="2">
                <input type="text" class="form-control decimal" name="details[{{ $loop->index }}][chieu_cao]" @if (isset($detail->chieu_cao))
                    value="{{ $detail->chieu_cao }}"
                @else
                    disabled
                @endif>
            </td>
            <td class="col-md-1" data-col-seq="2">
                <input type="text" class="form-control decimal" name="details[{{ $loop->index }}][chieu_dai]" @if (isset($detail->chieu_dai))
                    value="{{ $detail->chieu_dai }}"
                @else
                    disabled
                @endif>
            </td>
            <td class="col-md-1" data-col-seq="2">
                <input type="text" class="form-control decimal" name="details[{{ $loop->index }}][chieu_rong]" @if (isset($detail->chieu_rong))
                    value="{{ $detail->chieu_rong }}"
                @else
                    disabled
                @endif>
            </td>
            <td class="col-md-1" data-col-seq="2">
                <input type="text" class="form-control" name="details[{{ $loop->index }}][dien_tich]" readonly value="{{ $detail->dien_tich }}">
            </td>
            <td class="col-md-1" data-col-seq="2">
                <input type="text" class="form-control" name="details[{{ $loop->index }}][khoi_luong]" readonly value="{{ $detail->khoi_luong }}">
            </td>
            <td data-col-seq="4">
                <button class="btn btn-primary removeRow"><i class="fa fa-minus" aria-hidden="true"></i></button>
            </td>
        </tr>
        @php( $i++ )
    @endforeach
@endsection