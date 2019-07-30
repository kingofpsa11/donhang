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
            $('[name*="contract_detail_id"]').select2({
                placeholder: 'Chọn loại phôi'
            });
            $('[name*="product_id"]').select2({
                placeholder: 'Chọn loại vật tư'
            });
            
            $('tbody').on('change', '[name*="contract_detail_id"]', function () {
                
                let idOfProductBomDetail = $(this).find(':selected').data('product-id');
                let idOfBomDetail = $(this).find(':selected').data('bom-detail-id');
                let bomEl = $(this).parents('tr').find('[name*="product_id"]');
                
                //gán bom-detail-id cho input bom-detail-id
                $(this).parent().find('input').val(idOfBomDetail);
                
                //Xoá các option của select product-id
                bomEl.html('');

                $.ajax({
                    url: '{{ route('boms.get_bom') }}',
                    data: { productId: idOfProductBomDetail },
                    dataType: 'json',
                    success: function (data) {
                        if (Object.keys(data).length !== 0) {
                            bomEl.append(`<option value="" hidden>--Chọn loại phôi--</option>`);

                            $.each( data, function (i, el) {
                                bomEl.append(`<optgroup label="${el.name}"></optgroup>`);
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

        });
    </script>
@stop