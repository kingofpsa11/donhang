@extends('layouts.dashboard')

@section('title', 'Phiếu sản xuất')

@section('content')
	
	<!-- Main content -->
	<section class="content container-fluid">
		<form action=@yield('route') method="POST" id="form">
			@csrf
			@yield('method')
			<div class="box box-default">
				<div class="box-header with-border">
					<h3 class="box-title">Phiếu sản xuất</h3>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Số lệnh sản xuất</label>
                                <input type="text" class="form-control" name="number" value="{{ $manufacturerOrder->number ?? $manufacturerNote->manufacturerNoteDetails()->first()->contractDetail->manufacturerOrderDetail->manufacturerOrder->number }}" readonly>
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
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Đơn vị</label>
                                <input type="text" class="form-control">
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
							<th class="col-md-4 text-center">Tên sản phẩm</th>
                            <th class="col-md-4 text-center">Phôi</th>
							<th class="col-md-1 text-center">Số lượng</th>
							<th class="col-md-3 text-center">Ghi chú</th>
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
                        <a href="{{ url('manufacturer-notes') }}" class="btn btn-danger cancel">Hủy</a>
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
            
            let date = $('[name="manufacturerNote[date]"]');
            
            maskDate(date);
            
            $('tbody').on('change', '[name*="[contract_detail_id]"]', function () {
                let idOfProductBomDetail = $(this).find(':selected').data('product-id');
                let idOfBomDetail = $(this).find(':selected').data('bom-detail-id');
                let bomEl = $(this).parents('tr').find('[name*="product_id"]');
                
                //gán bom-detail-id cho input bom-detail-id
                $(this).parent().find('input').val(idOfBomDetail);
                
                //Xoá các option của select product-id
                bomEl.html('');

                $.ajax({
                    url: '{{ route('bom.getBom') }}',
                    data: { productId: idOfProductBomDetail },
                    dataType: 'json',
                    success: function (data) {
                        if (Object.keys(data).length !== 0) {
                            bomEl.append(`<option value="" hidden>--Chọn loại phôi--</option>`);
                            $.each( data, function (i, el) {
                                bomEl.append(`<optgroup  label="${el.name}"></optgroup>`);
                                $.each( el.bom_details, function (index, element) {
                                    bomEl.find('optgroup:last').append(`<option value="${element.product_id}">${element.product.name}</option>`);
                                });
                            });
                        } else {
                            bomEl.append(`<option value="">Chưa có định mức</option>`);
                        }
                    }
                });
            });
    
            function updateNumberOfRow() {
                let rows = $('tr[data-key]');
                rows.each(function (i, row) {
                    $(row).attr('data-key', i);
                    $(row).children('[data-col-seq="0"]').find('span').text(i + 1);
                    $(row).children('[data-col-seq="1"]').find('select').attr('name', 'manufacturerNoteDetails[' + (i) + '][contract_detail_id]');
                    $(row).children('[data-col-seq="1"]').find('input').attr('name', 'manufacturerNoteDetails[' + (i) + '][bom_detail_id]');
                    $(row).children('[data-col-seq="2"]').find('select').attr('name', 'manufacturerNoteDetails[' + (i) + '][product_id]');
                    $(row).children('[data-col-seq="3"]').find('input').attr('name', 'manufacturerNoteDetails[' + (i) + '][quantity]');
                    $(row).children('[data-col-seq="4"]').find('input').attr('name', 'manufacturerNoteDetails[' + (i) + '][note]');

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
                let tableBody = $('tbody');
                let numberOfProduct = tableBody.children().length;
                let lastRow = $('tr:last');
                let newRow = lastRow.clone();

                newRow.attr('data-key', numberOfProduct);
                newRow.children('[data-col-seq="0"]').find('span').text(numberOfProduct + 1);
                newRow.children('[data-col-seq="1"]').find('select').attr('name', 'manufacturerNoteDetails[' + (numberOfProduct) + '][contract_detail_id]');
                newRow.children('[data-col-seq="1"]').find('input').attr('name', 'manufacturerNoteDetails[' + (numberOfProduct) + '][bom_detail_id]');
                newRow.children('[data-col-seq="2"]').find('select').attr('name', 'manufacturerNoteDetails[' + (numberOfProduct) + '][product_id]');
                newRow.children('[data-col-seq="3"]').find('input').attr('name', 'manufacturerNoteDetails[' + (numberOfProduct) + '][quantity]');
                newRow.children('[data-col-seq="4"]').find('input').attr('name', 'manufacturerNoteDetails[' + (numberOfProduct) + '][note]');
                lastRow.find('button.removeRow').removeClass('hidden');
                newRow.find('button.removeRow').removeClass('hidden');
                newRow.find('input[name*="quantity"]').val('');
                newRow.find('input[name*="note"]').val('');
                tableBody.append(newRow);
            });
    
            $('#example1').on('click', '.removeRow', function () {
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
                convertDateToTimestamp($('[name="date"]'));
            });
        });
    </script>
@show