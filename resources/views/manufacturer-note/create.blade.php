@extends('manufacturer-note._form')

@section('action', 'Tạo phiếu cắt phôi')

@section('route')
    {{ route('manufacturer-notes.store') }}
@endsection

@section('table-body')
    @php($count = old('details') ? count(old('details')) : 1)
    @for ($i = 0; $i < $count; $i++)
        <tr data-key="0">
            <td data-col-seq="0">
                <span>1</span>
            </td>
            <td data-col-seq="1">
                <p class="manufacturer-order-number"></p>
                <input type="hidden" name="details[{{ $i }}][manufacturer-order-number]" value="{{ old('details')[$i]['manufacturer-order-number'] }}">
            </td>
            <td data-col-seq="2">
                <select name="details[{{ $i }}][contract_detail_id]" class="form-control" style="width: 100%" required>
                </select>
                <select class="form-control" name="details[{{ $i }}][product_id]" style="width: 100%" required>
                </select>
                <input type="hidden" class="bom-detail-id" name="details[{{ $i }}][bom_detail_id]">
            </td>
            <td data-col-seq="3">
                <input type="text" class="form-control" name="details[{{ $i }}][length]" required>
            </td>
            <td data-col-seq="3">
                <input type="text" class="form-control" name="details[{{ $i }}][thickness]" required>
            </td>
            <td data-col-seq="3">
                <input type="text" class="form-control" name="details[{{ $i }}][top_perimeter]" required>
            </td>
            <td data-col-seq="3">
                <input type="text" class="form-control" name="details[{{ $i }}][bottom_perimeter]" required>
            </td>
            <td data-col-seq="3">
                <div class="form-group @error('details.'.$i.'.quantity') has-error @enderror">
                    <input type="text" class="form-control" name="details[{{ $i }}][quantity]" required>
                    @error('details.'.$i.'.quantity')
                        <span class="help-block text-sm">{{ $message }}</span>
                    @enderror
                </div>
            </td>
            <td data-col-seq="4">
                <input type="text" class="form-control" name="details[{{ $i }}][note]">
            </td>
            <td data-col-seq="5">
                <button class="btn btn-primary removeRow hidden"><i class="fa fa-minus" aria-hidden="true"></i></button>
            </td>
        </tr>
    @endfor
@endsection