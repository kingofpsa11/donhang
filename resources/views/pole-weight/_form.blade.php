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
                                <select name="product_id" class="form-control">
                                    <option value="0">1</option>
                                    <option value="1">2</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="" class="control-label">Tên sản phẩm</label>
                                <input type="text" name="tong_khoi_luong" id="tong_khoi_luong" class="form-control" readonly>
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
                            <th>Mã vật tư</th>
                            <th>Tên vật tư</th>
                            <th>Số lượng</th>
                            <th>Ghi chú</th>
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
            function maskNumber(el) {
                el.inputmask('numeric', {
                    groupSeparator  : ".",
                    autoGroup       : true,
                    digits          : '2',
                    radixPoint      : ",",
                    removeMaskOnSubmit: true,
                    unmaskAsNumber: true,
                });
            }

            // function updateNumberOfRow() {
            //     let rows = $('tr[data-key]');
            //     rows.each(function (i, row) {
            //         $(row).attr('data-key', i);
            //         $(row).children('[data-col-seq="0"]').find('span').text(i + 1);
            //         if (i === 0) {
            //             if (rows.length === 1) {
            //                 $(row).find('button.removeRow').addClass('hidden');
            //             } else {
            //                 $(row).find('button.removeRow').removeClass('hidden');
            //             }
            //         }
            //     });
            // }
            //
            // //Add or remove row to table
            // $('.box-footer').on('click', '.addRow:not(".disabled")',function (e) {
            //     e.preventDefault();
            //     let tableBody = $('#form tbody');
            //     let numberOfProduct = tableBody.children().length;
            //     let lastRow = tableBody.find('tr:last');
            //     let newRow = lastRow.clone();
            //     let select2 = newRow.find('[name*=product_id]');
            //
            //     newRow.attr('data-key', numberOfProduct);
            //     newRow.children('[data-col-seq="0"]').find('span').text(numberOfProduct + 1);
            //
            //     lastRow.find('button.removeRow').removeClass('hidden');
            //     newRow.find('button.removeRow').removeClass('hidden');
            //     newRow.find('.select2-container').remove();
            //     newRow.find('option').remove();
            //     newRow.find('input').val('');
            //     tableBody.append(newRow);
            //
            //     maskNumber(newRow.find('[name*="quantity"]'));
            // });
            //
            // $('#table').on('click', '.removeRow', function (e) {
            //     e.preventDefault();
            //     let currentRow = $(this).parents('tr');
            //     currentRow.remove();
            //     updateNumberOfRow();
            // });
        })
    </script>
@stop