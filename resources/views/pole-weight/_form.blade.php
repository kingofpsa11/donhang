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
                                <select name="product_id" class="form-control"></select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="" class="control-label">Nhóm sản phẩm</label>
                                <select name="category" id="category" class="form-control">
                                    <option hidden>--Chọn nhóm--</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-1">
                            <div class="form-group">
                                <label for="" class="control-label">Tỷ lệ nhân công</label>
                                <input type="text" name="ty_le_nhan_cong" id="ty_le_nhan_cong" class="form-control decimal" readonly>
                            </div>
                        </div>
                        <div class="col-md-1">
                            <div class="form-group">
                                <label for="" class="control-label">Diện tích</label>
                                <input type="text" name="area" id="area" class="form-control decimal" readonly>
                            </div>
                        </div>
                        <div class="col-md-1">
                            <div class="form-group">
                                <label for="" class="control-label">Khối lượng</label>
                                <input type="text" name="weight" id="weight" class="form-control decimal" readonly>
                            </div>
                        </div>
                        <div class="col-md-1">
                            <div class="form-group">
                                <label for="" class="control-label">Đơn giá</label>
                                <input type="text" name="unit_price" id="unit_price" class="form-control number" readonly="">
                            </div>
                        </div>
                        <div class="col-md-1">
                            <div class="form-group">
                                <label for="" class="control-label">Thành tiền</label>
                                <input type="text" name="price" id="price" class="form-control number" readonly="">
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
                            <th>D ngọn</th>
                            <th>D gốc</th>
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
                        <a href="{{ route('boms.create') }}" class="btn btn-danger cancel">Hủy</a>
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
            
            $('#category').on('change', function () {
                $('#ty_le_nhan_cong').prop('readonly', false);
            });

            $('tbody').on('change', 'select', function () {
                let row = $(this).parents('tr');
                row.find('input').val('');
                row.find('input.decimal').prop('disabled', true);
                row.find('input.number').prop('disabled', true);

                if ($(this).val() === "0" ) {
                    row.find('[name*="day"]').prop('disabled', false);
                    row.find('[name*="chieu_dai"]').prop('disabled', false);
                    row.find('[name*="chieu_rong"]').prop('disabled', false);
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
                let hinh_dang = row.find('[name*="hinh_dang"]').val();
                let d_ngon = row.find('[name*="d_ngon"]').inputmask('unmaskedvalue');
                let d_goc = row.find('[name*="d_goc"]').inputmask('unmaskedvalue');
                let day = row.find('[name*="day"]').inputmask('unmaskedvalue');
                let chieu_dai = row.find('[name*="chieu_dai"]').inputmask('unmaskedvalue');
                let chieu_rong = row.find('[name*="chieu_rong"]').inputmask('unmaskedvalue');
                let chieu_cao = row.find('[name*="chieu_cao"]').inputmask('unmaskedvalue');
                let dien_tich;
                
                switch (hinh_dang) {
                    case "0":
                        dien_tich = chieu_dai * chieu_rong;
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
                $('#area').val(area);
                $('#weight').val(weight);
            }

            function updateNumberOfRow() {
                let rows = $('tr[data-key]');
                rows.each(function (i, row) {
                    $(row).attr('data-key', i);
                    $(row).children('[data-col-seq="0"]').find('span').text(i + 1);
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
                mask();
            });

            $('#table').on('click', '.removeRow', function (e) {
                e.preventDefault();
                let currentRow = $(this).parents('tr');
                currentRow.remove();
                updateNumberOfRow();
            });
        })
    </script>
@stop