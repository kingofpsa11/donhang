@extends('layouts.dashboard')

@section('title', 'Phiếu chuyển kho')

@section('action', 'Tạo phiếu')

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
                                <label>Đơn vị xuất hàng</label>
                                <input type="text" class="form-control" name="goodTransfer[delivery_store]" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Đơn vị nhập hàng</label>
                                <input type="text" class="form-control" name="goodTransfer[receive_store]" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Ngày</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input type="text" class="form-control" value="@yield('date')" name="goodTransfer[date]" required>
                                </div>
                                <!-- /.input group -->
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Số phiếu</label>
                                <input type="text" class="form-control" name="goodTransfer[number]" required>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body table-responsive">
                    <table id="example1" class="table table-bordered table-striped table-condensed">
                        <thead>
                        <tr>
                            <th>STT</th>
                            <th class="col-md-8">Tên sản phẩm</th>
                            <th class="col-md-2">Định mức</th>
                            <th class="col-md-2p">Số lượng</th>
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
                        <button class="btn btn-info addRow">Thêm dòng</button>
                        <input type="submit" value="Lưu" class="btn btn-success" name="saveDraft">
                        <input type="reset" value="Hủy" class="btn btn-danger">
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

            function maskDate(obj) {
                obj.inputmask({
                    'alias': 'dd/mm/yyyy'
                });
            }

            let date = $('[name$="[date]"]');

            maskDate(date);

            function addSelect2 (el) {
                el.select2({
                    placeholder: 'Nhập tên sản phẩm',
                    minimumInputLength: 2,
                    ajax: {
                        url: '{{ route('product.getProduct') }}',
                        delay: 200,
                        dataType: 'json',
                        dropdownAutoWidth : true,
                        processResults: function (data) {
                            return {
                                results: $.map(data, function (item) {
                                    return {
                                        text: item.name,
                                        id: item.id,
                                    }
                                })
                            };
                        },
                        cache: true
                    },
                });
            }

            function addSelect2Bom (el) {
                el.parents('tr').find('.product_id').on('select2:select', function () {

                    let product_id = el.parents('tr').find('input[name$="[product_id]"]').val();
                    el.html('');

                    $.ajax({
                        url: '{{ route('bom.getBom') }}',
                        data: {product_id: product_id},
                        dataType: 'json',
                        success: function (data) {
                            if (Object.keys(data).length !== 0) {
                                $.each(data, function (i, element) {
                                    el.append(`<option value="${element.id}">${element.name}</option>`);
                                });
                            } else {
                                el.append(`<option value="">Chưa có định mức</option>`);
                            }
                        }
                    });
                });
            }

            let product_select = $('.product_id');

            addSelect2(product_select);

            addSelect2Bom($('.bom_id'));

            function updateNumberOfRow() {
                let rows = $('tr[data-key]');
                rows.each(function (i, row) {
                    $(row).attr('data-key', i);
                    $(row).children('[data-col-seq="0"]').text(i + 1);
                    $(row).children('[data-col-seq="1"]').find('select').attr('name', 'goodTransferDetails[' + (i) + '][product_id]');
                    $(row).children('[data-col-seq="2"]').find('input').attr('name', 'goodTransferDetails[' + (i) + '][bom_id]');
                    $(row).children('[data-col-seq="3"]').find('input').attr('name', 'goodTransferDetails[' + (i) + '][quantity]');
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
                let tableBody = $('tbody');
                let numberOfProduct = tableBody.children().length;
                let lastRow = $('tr:last');
                let newRow = lastRow.clone();
                let select2 = newRow.find('.product_id');

                newRow.attr('data-key', numberOfProduct);
                newRow.children('[data-col-seq="0"]').text(numberOfProduct + 1);
                newRow.children('[data-col-seq="1"]').find('select').attr('name', 'goodTransferDetails[' + (numberOfProduct) + '][product_id]');
                newRow.children('[data-col-seq="2"]').find('input').attr('name', 'goodTransferDetails[' + (numberOfProduct) + '][bom_id]]');
                newRow.children('[data-col-seq="3"]').find('input').attr('name', 'goodTransferDetails[' + (numberOfProduct) + '][quantity]');
                lastRow.find('button.removeRow').removeClass('hidden');
                newRow.find('button.removeRow').removeClass('hidden');
                newRow.find('.select2-container').remove();
                newRow.find('option').remove();
                newRow.find('input').val('');
                tableBody.append(newRow);

                addSelect2(select2);
                addSelect2Bom(newRow.find('.bom_id'));
            });

            $('#example1').on('click', '.removeRow', function (e) {
                let currentRow = $(this).parents('tr');
                currentRow.remove();
                updateNumberOfRow();
            });

            //Click cancel button
            $('button.cancel').on('click', function (e) {
                e.preventDefault();
            });

            function convertDateToTimestamp(obj) {
                obj.each(function (i, el) {
                    let date = $(el).val();
                    $(el).inputmask('remove');
                    let datePart = date.split('/');
                    let newDate = new Date(datePart[2], datePart[1] - 1, datePart[0]);
                    $(el).val(newDate.getTime()/1000);
                });
            }

            $('#form').on('submit', function () {
                convertDateToTimestamp($('[name$="[date]"]'));
            });
        })
    </script>
@stop