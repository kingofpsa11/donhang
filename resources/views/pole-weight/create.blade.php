@extends('pole-weight._form')

@section('title', 'Tính khối lượng')

@section('action', 'Tạo mới')

@section('route')
    {{ route('pole-weight.store') }}
@endsection

@section('table-body')
    <tr data-key="0">
        <td data-col-seq="0">
            <span>1</span>
        </td>
        <td class="" data-col-seq="2">
            <input type="text" class="form-control" name="name[]" required >
        </td>
        <td class="">
            <select name="hinh_dang[]" id="" class="form-control">
                <option value="" hidden="">Chọn hình dạng</option>
                <option value="0">Thép tấm vuông</option>
                <option value="1">Thép tấm tròn</option>
                <option value="2">Ống tròn</option>
                <option value="3">Ống bát giác</option>
            </select>
        </td>
        <td class="col-md-1" data-col-seq="2">
            <input type="text" class="form-control quantity" name="quantity[]" required >
        </td>
        <td class="col-md-1" data-col-seq="2">
            <input type="text" class="form-control decimal" name="d_ngon[]" disabled="">
        </td>
        <td class="col-md-1" data-col-seq="2">
            <input type="text" class="form-control decimal" name="d_goc[]" disabled>
        </td>
        <td class="col-md-1" data-col-seq="2">
            <input type="text" class="form-control decimal" name="day[]" disabled>
        </td>
        <td class="col-md-1" data-col-seq="2">
            <input type="text" class="form-control number" name="chieu_cao[]" disabled>
        </td>
        <td class="col-md-1" data-col-seq="2">
            <input type="text" class="form-control decimal" name="chieu_dai[]" disabled>
        </td>
        <td class="col-md-1" data-col-seq="2">
            <input type="text" class="form-control decimal" name="chieu_rong[]" disabled>
        </td>
        <td class="col-md-1" data-col-seq="2">
            <input type="text" class="form-control" name="dien_tich[]" readonly>
        </td>
        <td class="col-md-1" data-col-seq="2">
            <input type="text" class="form-control" name="khoi_luong[]" readonly>
        </td>
        <td data-col-seq="4">
            <button class="btn btn-primary removeRow hidden"><i class="fa fa-minus" aria-hidden="true"></i></button>
        </td>
    </tr>
@endsection
