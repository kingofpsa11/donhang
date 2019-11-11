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
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="" class="control-label">Diện tích</label>
                                <input type="text" name="area" id="area" class="form-control decimal" readonly>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="" class="control-label">Khối lượng</label>
                                <input type="text" name="weight" id="weight" class="form-control decimal" readonly>
                            </div>
                        </div>
                        <div class="col-md-1">
                            <div class="form-group">
                                <label for="" class="control-label">Tỷ lệ nhân công</label>
                                <input type="text" name="ty_le_nhan_cong" id="ty_le_nhan_cong" class="form-control decimal">
                            </div>
                        </div>
                        <div class="col-md-1">
                            <div class="form-group">
                                <label for="" class="control-label">Đơn giá</label>
                                <input type="text" name="price" id="price" class="form-control number">
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
                            <th>Chủng loại</th>
                            <th>Chủng loại</th>
                            <th>Số lượng</th>
                            <th>D ngọn</th>
                            <th>D gốc</th>
                            <th>Chiều dày</th>
                            <th>Chiều cao</th>
                            <th>Chiều dài</th>
                            <th>Chiều rộng</th>
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

            $('.number').inputmask('integer', {
                groupSeparator  : ".",
                autoGroup       : true,
                removeMaskOnSubmit: true,
                unmaskAsNumber: true,
            });

            $('.decimal').inputmask('numeric', {
                groupSeparator  : ".",
                autoGroup       : true,
                digits          : '2',
                radixPoint      : ",",
                removeMaskOnSubmit: true,
                unmaskAsNumber: true,
            });

            $('tbody').on('change', 'select', function () {
                if ($(this).val() === "0" ) {
                    $(this).parents('tr').find('[name*="day"]').prop('disabled', false);
                    $(this).parents('tr').find('[name*="chieu_dai"]').prop('disabled', false);
                    $(this).parents('tr').find('[name*="chieu_rong"]').prop('disabled', false);
                } else if($(this).val() === "1" ) {
                    $(this).parents('tr').find('[name*="d_ngon"]').prop('disabled', false);
                    $(this).parents('tr').find('[name*="day"]').prop('disabled', false);
                }
            });

            $('tbody').on('keypress', 'input', function () {
                let row = $(this).parents('tr');
                let hinh_dang = row.find('[name*="hinh_dang"]').val();
                let d_ngon = row.find('[name*="d_ngon"]').inputmask('unmaskedvalue');
                let day = row.find('[name*="day"]').inputmask('unmaskedvalue');
                let chieu_dai = row.find('[name*="chieu_dai"]').inputmask('unmaskedvalue');
                let chieu_rong = row.find('[name*="chieu_rong"]').inputmask('unmaskedvalue');
                let dien_tich;
                
                switch (hinh_dang) {
                    case "0":
                        dien_tich = chieu_dai * chieu_rong;
                        break;
                    case "1":
                        dien_tich = d_ngon * d_ngon * PI;
                }
                if (hinh_dang === "0") {
                    dien_tich = chieu_dai * chieu_rong;
                }
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
                let total_value = 0;
                rows.each(function (i, el) {
                    let selling_price = $(el).find('[name*="khoi_luong"]').inputmask('unmaskedvalue');
                    let quantity = $(el).find('[name*="quantity"]').val();
                    total_value += selling_price * quantity;
                });

                console.log(total_value);
                $('#weight').val(total_value);
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
                tableBody.append(newRow);
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