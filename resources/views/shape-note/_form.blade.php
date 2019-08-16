@extends('layouts.dashboard')

@section('title', 'Phiếu cắt tấm')

@section('content')
	
	<!-- Main content -->
    <form action=@yield('route') method="POST" id="form">
        @csrf
        @yield('method')
        <div class="box box-default no-padding">
            <div class="box-header with-border">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Số phiếu</label>
                            <input type="text" class="form-control" name="number" value="{{ $shapeNote->number ?? $newNumber }}" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Ngày</label>
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input type="text" class="form-control"  name="date" value="{{ $shapeNote->date ?? date('d/m/Y') }}" required>
                            </div>
                            <!-- /.input group -->
                        </div>
                    </div>
                </div>
                <!-- /.row -->
            </div>
            <!-- /.box-header -->

            <div class="box-body table-responsive">
                <table id="example1" class="table table-bordered table-striped table-condensed">
                    <thead>
                    <tr>
                        <th>STT</th>
                        <th>Số LSX</th>
                        <th class="col-md-10">Tên sản phẩm</th>
                        <th class="col-md-2">Số lượng</th>
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
                    <a href="{{ route('shape-notes.index') }}" class="btn btn-danger cancel">Hủy</a>
                </div>
            </div>
        </div>
        <!-- /.box -->
    </form>
@endsection

@section('javascript')
    <script>
        $(document).ready(function () {
            function maskDate(obj) {
                obj.inputmask({
                    alias: 'date',
                    displayFormat: 'dd/mm/yyyy',
                });
            }

            let date = $('[name="date"]');

            maskDate(date);

            function addSelect2(el) {
                el.select2({
                    placeholder: 'Nhập số lệnh sản xuất',
                    ajax: {
                        url: '{{ route('manufacturer-notes.get-manufacturer-note') }}',
                        data: function (params) {
                            return {
                                search: params.term,
                            };
                        },
                        dataType: 'json',
                        processResults: function (data) {
                            return {
                                results: $.map(data, function (item) {
                                    return {
                                        text: item.name,
                                        id: item.id,
                                        number: item.number,
                                        quantity: item.quantity,
                                        product_id: item.product_id,
                                        contract_detail_id: item.contract_detail_id
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
                        return $(`<div class="container-fluid"><div class="row"><div class="col-md-8">${repo.text}</div><div class="col-md-2">${repo.number}</div><div class="col-md-2">${repo.quantity}</div></div></div> `);
                    },
                });

                el.on('select2:select', function (e) {
                    let data = e.params.data;
                    let product_id = data.product_id;
                    let bomEl = $(this).parents('tr').find('[name*="product_id"]');
                    $(this).parents('tr').find('.manufacturer_order_number').text(data.number);
                    $(this).parents('tr').find('input[name*="quantity"]').val(data.quantity);
                    $(this).parents('tr').find('input[name*="contract_detail_id"]').val(data.contract_detail_id);

                    bomEl.select2({
                        placeholder: 'Chọn phôi',
                        ajax: {
                            url: '{{ route('boms.get_bom') }}',
                            data: {productId: product_id},
                            dataType: 'json',
                            processResults: function (data) {
                                return {
                                    results: $.map(data, function (item) {
                                        return {
                                            text: item.product_name,
                                            id: item.product_id,
                                            code: item.code,
                                            quantity: item.quantity,
                                            name: item.name,
                                        }
                                    }),
                                };
                            },
                            cache: true
                        },
                        templateResult: function (repo) {
                            if (repo.loading) {
                                return 'Đang tìm kiếm';
                            }
                            return $(`<div class="container-fluid"><div class="row"><div class="col-md-2">${repo.code}</div><div class="col-md-8">${repo.text}</div><div class="col-md-2">${repo.name}</div></div></div> `);
                        },
                    });

                    bomEl.on('select2:select', function (e) {
                        let data = e.params.data;
                        $(this).parents('tr').find('[name*="code"]').val(data.code);
                    })
                });
            }

            let contract_detail_id = $('.contract_detail_id');
            addSelect2(contract_detail_id);

            function updateNumberOfRow() {
                let rows = $('tr[data-key]');

                rows.each(function (i, row) {
                    $(row).attr('data-key', i);
                    $(row).children('[data-col-seq="0"]').find('span').text(i + 1);
                    if (rows.length === 1) {
                        $(row).find('button.removeRow').addClass('hidden');
                    } else {
                        $(row).find('button.removeRow').removeClass('hidden');
                    }
                });
            }
            updateNumberOfRow();

            //Add or remove row to table
            $('.box-footer').on('click', '.addRow:not(".disabled")', function (e) {
                e.preventDefault();
                let tableBody = $('#example1 tbody');
                let numberOfProduct = tableBody.children().length;
                let lastRow = tableBody.find('tr:last');
                let newRow = lastRow.clone();
                let select2 = newRow.find('.contract_detail_id');

                newRow.attr('data-key', numberOfProduct);
                newRow.children('[data-col-seq="0"]').find('span').text(numberOfProduct + 1);
                lastRow.find('button.removeRow').removeClass('hidden');
                newRow.find('button.removeRow').removeClass('hidden');
                newRow.find('.select2-container').remove();
                newRow.find('option').remove();
                newRow.find('input').val('');
                tableBody.append(newRow);

                addSelect2(select2);
            });

            $('#example1').on('click', '.removeRow', function (e) {
                e.preventDefault();
                let currentRow = $(this).parents('tr');
                currentRow.remove();
                updateNumberOfRow();
            });

            //Click cancel button
            $('button.cancel').on('click', function (e) {
                e.preventDefault();
            });
        });
    </script>
@stop