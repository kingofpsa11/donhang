@extends('pole-weight._form')

@section('title', 'Tính khối lượng')

@section('action', 'Tạo mới')

@section('route')
    {{ route('pole-weight.store') }}
@endsection

@section('table-body')
    <tr data-key="0">
        <td data-col-seq="0">1</td>
        <td class="col-md-2">
            <select name="hinh_dang[]" id="" class="form-control">
                <option value="" hidden="">--Chọn hình dạng--</option>
                <option value="0">Thép tấm</option>
                <option value="1">Ống tròn</option>
                <option value="2">Ống bát giác</option>
            </select>
        </td>
        <td class="col-md-2" data-col-seq="1">
            <select name="bom_product_id[]" required class="form-control" style="width: 100%;"></select>
        </td>
        <td class="col-md-1" data-col-seq="2">
            <input type="text" class="form-control" name="quantity[]" required>
        </td>
        <td class="col-md-1" data-col-seq="2">
            <input type="text" class="form-control" name="d_ngon[]" required>
        </td>
        <td class="col-md-1" data-col-seq="2">
            <input type="text" class="form-control" name="d_goc[]" required>
        </td>
        <td class="col-md-1" data-col-seq="2">
            <input type="text" class="form-control" name="day[]" required>
        </td>
        <td class="col-md-1" data-col-seq="2">
            <input type="text" class="form-control" name="chieu_cao[]" required>
        </td>
        <td class="col-md-1" data-col-seq="2">
            <input type="text" class="form-control" name="chieu_dai[]" required>
        </td>
        <td class="col-md-1" data-col-seq="2">
            <input type="text" class="form-control" name="chieu_rong[]" required>
        </td>
        <td class="col-md-1" data-col-seq="2">
            <input type="text" class="form-control" name="khoi_luong[]" required>
        </td>
        <td data-col-seq="4">
            <button class="btn btn-primary removeRow hidden"><i class="fa fa-minus" aria-hidden="true"></i></button>
        </td>
    </tr>
@endsection
