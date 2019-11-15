@extends('layouts.dashboard')

@section('content')

    <!-- Main content -->
    <section class="content container-fluid">
        <form action="@yield('route')" method="POST" id="form">
            @csrf
            @yield('method')

            <div class="box">
                <div class="box-header">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="" class="control-label">Tên sản phẩm</label>
                                <select name="product_id" id="product_id" class="form-control" required>
                                    @if (isset($poleWeight))
                                        <option value="{{ $poleWeight->product_id }}">{{ $poleWeight->product->name }}</option>
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="" class="control-label">Nhóm sản phẩm</label>
                                <select name="expense_of_pole_id" id="expense_of_pole_id" class="form-control" required>
                                    <option hidden>--Chọn nhóm--</option>
                                    @foreach($categories as $category)
                                        @if (isset($poleWeight) && $category->id === $poleWeight->expense_of_pole_id))
                                            <option value="{{ $category->id }}" selected>{{ $category->name }}</option>
                                        @else
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-1">
                            <div class="form-group">
                                <label for="" class="control-label">Tỷ lệ nhân công</label>
                                <input type="text" name="ty_le_nhan_cong" id="ty_le_nhan_cong" class="form-control decimal" disabled required value="{{ $poleWeight->ty_le_nhan_cong ?? ''}}">
                            </div>
                        </div>
                        <div class="col-md-1">
                            <div class="form-group">
                                <label for="" class="control-label">Diện tích</label>
                                <input type="text" name="area" id="area" class="form-control decimal" readonly value="{{ $poleWeight->area ?? ''}}">
                            </div>
                        </div>
                        <div class="col-md-1">
                            <div class="form-group">
                                <label for="" class="control-label">Khối lượng</label>
                                <input type="text" name="weight" id="weight" class="form-control decimal" readonly value="{{ $poleWeight->weight ?? ''}}">
                            </div>
                        </div>
                        <div class="col-md-1">
                            <div class="form-group">
                                <label for="" class="control-label">Đơn giá</label>
                                <input type="text" name="unit_price" id="unit_price" class="form-control number" readonly value="{{ $poleWeight->unit_price ?? ''}}">
                            </div>
                        </div>
                        <div class="col-md-1">
                            <div class="form-group">
                                <label for="" class="control-label">Thành tiền</label>
                                <input type="text" name="price" id="price" class="form-control number" readonly value="{{ $poleWeight->price ?? ''}}">
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body table-responsive">
                    <table id="table" class="table table-bordered table-striped table-condensed">
                        <thead>
                        <tr>
                            <th>STT</th>
                            <th>Tên chi tiết</th>
                            <th>Chủng loại</th>
                            <th>Số lượng</th>
                            <th>D ngọn/D ngoài</th>
                            <th>D gốc/D trong</th>
                            <th>Chiều dày</th>
                            <th>Chiều cao</th>
                            <th>Chiều dài</th>
                            <th>Chiều rộng</th>
                            <th>Diện tích</th>
                            <th>Khối lượng</th>
                        </tr>
                        </thead>
                        <tbody>
                            @yield('table-body')
                        </tbody>
                    </table>
                </div>
                <!-- /.box-body -->
                <div class="box-footer text-right">
                    <div>
                        <button class="btn btn-primary addRow">Thêm dòng</button>
                        <input type="submit" value="Lưu" class="btn btn-success save">
                        <a href="{{ route('pole-weight.index') }}" class="btn btn-danger cancel">Hủy</a>
                    </div>
                </div>
            </div>
            <!-- /.box -->
        </form>
    </section>
@endsection

@section('javascript')
    <script>
        $(document).ready(function () {
            const khoi_luong_rieng = 7850/1e9;
            let categoryObj = $('#expense_of_pole_id');
            let ty_le_nhan_cong_Obj = $('#ty_le_nhan_cong');

            $('#product_id').select2({
                placeholder: 'Nhập sản phẩm',
                minimumInputLength: 2,
                ajax: {
                    url         : '{{ route('products.get_product') }}',
                    delay       : 200,
                    dataType    : 'json',
                    processResults: function (data) {
                        return {
                            results: $.map(data, function (item) {
                                return {
                                    text    : item.name,
                                    id      : item.id,
                                }
                            })
                        };
                    },
                    cache: true
                }
            });

            function mask() {
                $('.number').inputmask('integer', {
                    groupSeparator: ".",
                    autoGroup: true,
                    removeMaskOnSubmit: true,
                    unmaskAsNumber: true,
                });

                $('.decimal').inputmask('numeric', {
                    groupSeparator: ".",
                    autoGroup: true,
                    digits: '2',
                    radixPoint: ",",
                    removeMaskOnSubmit: true,
                    unmaskAsNumber: true,
                });
            }

            mask();

            categoryObj.on('change', function () {
                if (ty_le_nhan_cong_Obj.val() === '') {
                    ty_le_nhan_cong_Obj.prop('disabled', false);
                    ty_le_nhan_cong_Obj.val(1);
                }

                getUnitPrice();
            });

            function getUnitPrice() {
                let category = categoryObj.val();
                let ty_le_nhan_cong = ty_le_nhan_cong_Obj.inputmask('unmaskedvalue');
                $.get(
                    '{{ route('expense-of-pole.getUnitPrice') }}',
                    {category: category, ty_le_nhan_cong: ty_le_nhan_cong},
                    function (result) {
                        $('#unit_price').val(result);
                    },
                    'text'
                );
            }

            ty_le_nhan_cong_Obj.on('focusout', getUnitPrice);

            $('tbody').on('change', 'select', function () {
                let row = $(this).parents('tr');
                row.find('input').val('');
                row.find('input.decimal').prop('disabled', true);
                row.find('input.number').prop('disabled', true);

                if ($(this).val() === "0" ) {
                    row.find('[name*="day"]').prop('disabled', false);
                    row.find('[name*="chieu_dai"]').prop('disabled', false);
                    row.find('[name*="chieu_rong"]').prop('disabled', false);
                    row.find('[name*="d_goc"]').prop('disabled', false);
                } else if($(this).val() === "1" ) {
                    row.find('[name*="d_ngon"]').prop('disabled', false);
                    row.find('[name*="d_goc"]').prop('disabled', false);
                    row.find('[name*="day"]').prop('disabled', false);
                } else if($(this).val() === "2" || $(this).val() === "3" ) {
                    row.find('[name*="d_ngon"]').prop('disabled', false);
                    row.find('[name*="d_goc"]').prop('disabled', false);
                    row.find('[name*="day"]').prop('disabled', false);
                    row.find('[name*="chieu_cao"]').prop('disabled', false);
                }
            });

            $('tbody').on('keyup', 'input', function () {
                let row = $(this).parents('tr');
                let shape = row.find('[name*="shape"]').val();
                let d_ngon = row.find('[name*="d_ngon"]').inputmask('unmaskedvalue');
                let d_goc = row.find('[name*="d_goc"]').inputmask('unmaskedvalue');
                let day = row.find('[name*="day"]').inputmask('unmaskedvalue');
                let chieu_dai = row.find('[name*="chieu_dai"]').inputmask('unmaskedvalue');
                let chieu_rong = row.find('[name*="chieu_rong"]').inputmask('unmaskedvalue');
                let chieu_cao = row.find('[name*="chieu_cao"]').inputmask('unmaskedvalue');
                let dien_tich;

                switch (shape) {
                    case "0":
                        dien_tich = chieu_dai * chieu_rong - d_goc * d_goc * Math.PI / 4;
                        break;
                    case "1":
                        dien_tich = (d_ngon * d_ngon - d_goc * d_goc) * Math.PI / 4;
                        break;
                    case  "2":
                        dien_tich = (d_ngon + d_goc - 2 * day) / 2 * Math.PI * chieu_cao;
                        break;
                    case  "3":
                        dien_tich = (d_ngon + d_goc - 2 * day) / 2 * 3.265 * chieu_cao;
                        break;
                }

                row.find('[name*="dien_tich"]').val(Math.round(dien_tich / 1e4) / 100);
                let khoi_luong = Math.round(dien_tich * day * khoi_luong_rieng * 100)/100;
                let khoi_luong_obj = row.find('[name*="khoi_luong"]');
                khoi_luong_obj.val(khoi_luong);
                khoi_luong_obj.inputmask('numeric', {
                    groupSeparator  : ".",
                    autoGroup       : true,
                    digits          : '2',
                    radixPoint      : ",",
                    removeMaskOnSubmit: true,
                    unmaskAsNumber: true,
                });

                calculate();
            });
            
            function calculate() {
                let rows = $('tr[data-key]');
                let weight = 0;
                let area = 0;

                rows.each(function (i, el) {
                    let dien_tich = $(el).find('[name*="dien_tich"]').inputmask('unmaskedvalue');
                    let khoi_luong = $(el).find('[name*="khoi_luong"]').inputmask('unmaskedvalue');
                    let quantity = $(el).find('[name*="quantity"]').val();
                    area += dien_tich * quantity;
                    weight += khoi_luong * quantity;
                });

                let price = Math.round(weight * $('#unit_price').inputmask('unmaskedvalue') / 100) * 100;

                $('#area').val(area);
                $('#weight').val(weight);
                $('#price').val(price);
            }

            function updateNumberOfRow() {
                let rows = $('tr[data-key]');
                rows.each(function (i, row) {
                    $(row).attr('data-key', i);
                    $(row).children('[data-col-seq="0"]').find('span').text(i + 1);
                    $(row).find('[name]').each(function (index, el) {
                        let oldName = $(el).attr('name');
                        let pos = oldName.indexOf('[');
                        let newName = oldName.substr(0, pos + 1) + i + oldName.substr(pos + 2);
                        $(el).attr('name', newName);
                    });
                    if (i === 0) {
                        if (rows.length === 1) {
                            $(row).find('button.removeRow').addClass('hidden');
                        } else {
                            $(row).find('button.removeRow').removeClass('hidden');
                        }
                    }
                });
            }

            //Add or remove row to table
            $('.box-footer').on('click', '.addRow:not(".disabled")',function (e) {
                e.preventDefault();
                let tableBody = $('#form tbody');
                let numberOfProduct = tableBody.children().length;
                let lastRow = tableBody.find('tr:last');
                let newRow = lastRow.clone();

                newRow.attr('data-key', numberOfProduct);
                newRow.children('[data-col-seq="0"]').find('span').text(numberOfProduct + 1);

                lastRow.find('button.removeRow').removeClass('hidden');
                newRow.find('button.removeRow').removeClass('hidden');
                newRow.find('.select2-container').remove();
                // newRow.find('option').remove();
                newRow.find('input').val('');
                newRow.find('input.decimal').prop('disabled', true);
                newRow.find('input.number').prop('disabled', true);
                tableBody.append(newRow);
                updateNumberOfRow();
                mask();
            });

            $('#table').on('click', '.removeRow', function (e) {
                e.preventDefault();
                let currentRow = $(this).parents('tr');
                currentRow.remove();
                updateNumberOfRow();
                calculate();
            });
        })
    </script>
@stop