@extends('layouts.dashboard')

@section('title', 'Phiếu nhập kho')

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
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Đơn vị giao hàng</label>
                                <select class="form-control" name="supplier_id" required style="width: 100%;">
                                    @if (isset($goodReceive))
                                        <option value="{{ $goodReceive->supplier_id }}">{{ $goodReceive->supplier->name }}</option>
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Người giao</label>
                                <input type="text" class="form-control" name="supplier_user" value="{{ $goodReceive->supplier_user ?? '' }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Ngày</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input type="text" class="form-control" value="{{ $goodReceive->date ?? date('d/m/Y') }}" name="date" required>
                                </div>
                                <!-- /.input group -->
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Số phiếu</label>
                                <input type="text" class="form-control" name="number" value="{{ $newNumber ?? $goodReceive->number }}" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Ghi chú</label>
                                <input type="text" class="form-control" name="note" value="{{ $goodReceive->note ?? '' }}">
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
                            <th class="col-md-1">Mã sản phẩm</th>
                            <th class="col-md-5">Tên sản phẩm</th>
                            <th class="col-md-1">Đvt</th>
                            @role(4)
                            <th class="col-md-2">Định mức</th>
                            <th class="col-md-1">Kho</th>
                            <th class="col-md-2">Số lượng</th>
                            @else
                            <th class="col-md-2">Kho</th>
                            <th class="col-md-3">Số lượng</th>
                            @endrole
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
                        <input type="submit" value="Lưu" class="btn btn-success" name="saveDraft">
                        @if( isset($goodReceive) )
                            <a href="{{ route('good-receive.show', $goodReceive) }}" class="btn btn-danger">Hủy</a>
                        @endif
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

            let date = $('[name*="[date]"]');

            maskDate(date);

            function addSupplierSelect2 (el) {
                el.select2({
                    placeholder: 'Nhập đơn vị giao hàng',
                    minimumInputLength: 2,
                    ajax: {
                        url: '{{ route('supplier.listSupplier') }}',
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

            function addStoreSelect2 (el) {
                el.select2({
                    placeholder: 'Nhập kho',
                    minimumInputLength: 1,
                    dropdownCssClass: 'bigdrop',
                    ajax: {
                        url: '{{ route('store.listStore') }}',
                        delay: 200,
                        dataType: 'json',
                        // dropdownAutoWidth : true,
                        processResults: function (data) {
                            return {
                                results: $.map(data, function (item) {
                                    return {
                                        text: item.code,
                                        id: item.id,
                                        name: item.name
                                    }
                                })
                            };
                        },
                        cache: true
                    },
                    templateResult: function (repo) {
                        if (repo.loading) {
                            return 'Đang tìm kiếm';
                        }
                        return $(`<div class="container-fluid"><div class="row"><div class="col-md-4">${repo.text}</div><div class="col-md-8">${repo.name}</div></div></div> `);
                    },
                });
            }

            function addSelect2Bom (el) {
                let productId = el.parents('tr').find('[name*="product_id"]').val();
                el.html('');

                $.ajax({
                    url: '{{ route('bom.getBom') }}',
                    data: {productId: productId},
                    dataType: 'json',
                    success: function (data) {
                        if (Object.keys(data).length !== 0) {
                            el.append(`<option value="" hidden>--Chọn định mức sản phẩm--</option>`);
                            $.each(data, function (i, element) {
                                el.append(`<option value="${element.id}">${element.name}</option>`);
                            });
                        } else {
                            el.append(`<option value="">--Không dùng định mức--</option>`);
                        }
                    }
                });
            }

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
                                        product_id: item.product_id,
                                        code: item.code
                                    }
                                })
                            };
                        },
                        cache: true
                    },
                });

                el.on('select2:select', function (e) {
                    let data = e.params.data;
                    let row = el.closest('tr');
                    addSelect2Bom(row.find('[name*="bom_id"]'));
                    addStoreSelect2(row.find('[name*="store_id"]'));
                    row.find('input[name*="code"]').val(data.code);
                });
            }

            let productElement = $('.product_id');
            addSelect2(productElement);
            addStoreSelect2($('[name*="store_id"]'));
            let supplier = $('[name*="supplier_id"]');
            addSupplierSelect2(supplier);

            function updateNumberOfRow() {
                let rows = $('tr[data-key]');
                rows.each(function (i, row) {
            //         $(row).attr('data-key', i);
                    $(row).children('[data-col-seq="0"]').find('span', i + 1);
            //         $(row).children('[data-col-seq="1"]').find('input').attr('name', 'goodReceiveDetails[' + i + '][code]');
            //         $(row).children('[data-col-seq="2"]').find('select').attr('name', 'goodReceiveDetails[' + i + '][product_id]');
            //         $(row).children('[data-col-seq="3"]').find('input').attr('name', 'goodReceiveDetails[' + i + '][unit]');
            //         $(row).children('[data-col-seq="4"]').find('select').attr('name', 'goodReceiveDetails[' + i + '][bom_id]');
            //         $(row).children('[data-col-seq="5"]').find('select').attr('name', 'goodReceiveDetails[' + i + '][store_id]');
            //         $(row).children('[data-col-seq="6"]').find('input').attr('name', 'goodReceiveDetails[' + i + '][quantity]');
            //
                    if (rows.length === 1) {
                        $(row).find('button.removeRow').addClass('hidden');
                    } else {
                        $(row).find('button.removeRow').removeClass('hidden');
                    }
                });
            }
            //
            updateNumberOfRow();

            //Add or remove row to table
            $('.box-footer').on('click', '.addRow:not(".disabled")',function (e) {
                e.preventDefault();
                let tableBody = $('tbody');
                let numberOfProduct = tableBody.children().length;
                let lastRow = $('tr:last');
                let newRow = lastRow.clone();
                let select2 = newRow.find('.product_id');

                // newRow.attr('data-key', numberOfProduct);
                newRow.children('[data-col-seq="0"]').text(numberOfProduct + 1);
                // newRow.children('[data-col-seq="2"]').find('select').attr('name', 'goodReceiveDetails[' + (numberOfProduct) + '][product_id]');
                // newRow.children('[data-col-seq="3"]').find('input').attr('name', 'goodReceiveDetails[' + (numberOfProduct) + '][unit]');
                // newRow.children('[data-col-seq="4"]').find('select').attr('name', 'goodReceiveDetails[' + (numberOfProduct) + '][bom_id]');
                // newRow.children('[data-col-seq="5"]').find('select').attr('name', 'goodReceiveDetails[' + (numberOfProduct) + '][store_id]');
                // newRow.children('[data-col-seq="6"]').find('input').attr('name', 'goodReceiveDetails[' + (numberOfProduct) + '][quantity]');
                lastRow.find('button.removeRow').removeClass('hidden');
                newRow.find('button.removeRow').removeClass('hidden');
                newRow.find('.select2-container').remove();
                newRow.find('option').remove();
                newRow.find('input').val('');
                tableBody.append(newRow);

                addSelect2(select2);
                addStoreSelect2(newRow.find('[name*="store_id"]'));
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