@extends('step-note._form')

@section('action', 'Tạo phiếu')

@section('route')
    {{ route('step-notes.store') }}
@endsection

@section('table-body')
    @php($count = old('details') ? count(old('details')) : 1)
{{--    @php($count = 1)--}}
    @for ($i = 0; $i < $count; $i++)
        <tr data-key="0">
            <td data-col-seq="0">
                <span>1</span>
            </td>
            <td data-col-seq="1">
                <input type="text" class="form-control manufacturer" name="details[{{ $i }}][manufacturer_order_number]" value="{{ old('details')[$i]['manufacturer_order_number'] }}" readonly>
            </td>
            <td data-col-seq="2">
                <input type="text" class="form-control" name="details[{{ $i }}][code]" value="{{ old('details')[$i]['code'] }}" readonly>
            </td>
            <td data-col-seq="3">
                <input type="hidden" name="details[{{ $i }}][contract_detail_id]" value="{{ old('details')[$i]['contract_detail_id'] }}">
                <input type="hidden" name="details[{{ $i }}][product_id]" value="{{ old('details')[$i]['product_id'] }}">
                <select type="text" class="form-control contract_product" name="details[{{ $i }}][contract_product]" style="width:100%" required>
                    @if (old('details'))
                        <option value="{{ old('details')[$i]['contract_product'] }}">{{ \App\Product::find(explode('-', old('details')[$i]['contract_product'])[1])->name }}</option>
                    @endif
                </select>
            </td>
            <td data-col-seq="4">
                <div class="form-group @error('details.' . $i . '.quantity') has-error @enderror">
                    <input type="number" class="form-control" name="details[{{ $i }}][quantity]" required>
                    @error('details.' . $i . '.quantity')
                    <span id="helpBlock" class="help-block">{{ $message }}</span>
                    @enderror
                </div>
            </td>
            <td data-col-seq="5">
                <button class="btn btn-primary removeRow hidden"><i class="fa fa-minus" aria-hidden="true"></i></button>
            </td>
        </tr>
    @endfor
@endsection