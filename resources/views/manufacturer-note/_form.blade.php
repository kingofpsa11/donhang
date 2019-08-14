@extends('layouts.dashboard')

@section('title', 'Phiếu cắt phôi')

@section('content')
	
	<!-- Main content -->
	<section class="content container-fluid">
		<form action=@yield('route') method="POST" id="form">
            @csrf
            @yield('method')
            <div class="box box-default">
                <div class="box-header with-border">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Số phiếu</label>
                                <input type="text" class="form-control" name="number" value="{{ $manufacturerNote->number ?? $newNumber }}" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Ngày</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input type="text" class="form-control" value="{{ $manufacturerNote->date ?? date('d/m/Y') }}" name="date" required>
                                </div>
                                <!-- /.input group -->
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
                            <th>LSX</th>
                            <th class="col-md-5 text-center">Tên sản phẩm</th>
                            <th>Dài</th>
                            <th>Dày</th>
                            <th>Chi vi trên</th>
                            <th>Chi vi dưới</th>
                            <th class="text-center">Số lượng</th>
                            <th class="col-md-2 text-center">Ghi chú</th>
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
                        <a href="{{ route('manufacturer-notes.index') }}" class="btn btn-danger cancel">Hủy</a>
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
                    alias: 'date',
                    displayFormat: 'dd/mm/yyyy',
                });
            }
            
            let date = $('[name*="date"]');
            
            maskDate(date);

            let contract = $('[name*="contract_detail_id"]');
            addSelect2(contract);

            function addSelect2(el) {
                el.select2({
                    placeholder: 'Nhập LSX',
                    minimumInputLength: 0,
                    ajax: {
                        url: '{{ route('manufacturer-orders.get_manufacturers_by_status') }}',
                        delay: 200,
                        data: function (params) {
                            return {
                                search: params.term,
                            };
                        },
                        dataType: 'json',
                        dropdownAutoWidth: true,
                        processResults: function (data) {
                            return {
                                results: $.map(data, function (item) {
                                    return {
                                        text: item.name,
                                        id: item.id,
                                        quantity: item.quantity,
                                        code: item.code,
                                        number: item.number,
                                        product: item.product
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



                //

                el.on('select2:select', function (e) {
                    let data = e.params.data;
                    let row = el.parents('tr');
                    let bomEl = row.find('[name*="product_id"]');
                    row.find('[name*="quantity"]').val(data.quantity);
                    row.find('.manufacturer-order-number').text(data.number);

                    //Xoá các option của select product-id
                    bomEl.html('');

                    $.ajax({
                        url: '{{ route('boms.get_bom') }}',
                        data: {productId: data.product},
                        dataType: 'json',
                        success: function (data) {
                            if (Object.keys(data).length !== 0) {
                                $.each(data, function (i, el) {
                                    bomEl.append(`<optgroup label="${el.name}"></optgroup>`);
                                    $.each(el.bom_details, function (index, element) {
                                        bomEl.find('optgroup:last').append(`<option value="${element.product_id}">${element.product.name}</option>`);
                                    });
                                });

                                bomEl.select2({
                                    placeholder: 'Chọn loại phôi'
                                });

                            } else {
                                bomEl.append(`<option value="">Chưa có định mức</option>`);
                            }
                        }
                    });
                });
            }
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
            $('.box-footer').on('click', '.addRow:not(".disabled")',function (e) {
                e.preventDefault();
                let tableBody = $('#example1 tbody');
                let numberOfProduct = tableBody.children().length;
                let lastRow = tableBody.find('tr:last');
                let newRow = lastRow.clone();
                let select2 = newRow.find('[name*=contract_detail_id]');

                newRow.attr('data-key', numberOfProduct);
                newRow.children('[data-col-seq="0"]').find('span').text(numberOfProduct + 1);

                lastRow.find('button.removeRow').removeClass('hidden');
                newRow.find('button.removeRow').removeClass('hidden');

                newRow.find('.select2-container').remove();
                newRow.find('select').html('');

                newRow.find('input[name*="quantity"]').val('');
                newRow.find('input[name*="note"]').val('');

                addSelect2(select2);

                tableBody.append(newRow);
            });
    
            $('#example1').on('click', '.removeRow', function () {
                let currentRow = $(this).parents('tr');
                currentRow.remove();
                updateNumberOfRow();
            });
        });
    </script>
@stop