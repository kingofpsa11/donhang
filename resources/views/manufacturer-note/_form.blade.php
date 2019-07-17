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
                                <input type="text" class="form-control" name="manufacturerNote[number]" value="{{ $manufacturerOrder->number ?? '' }}" readonly>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Ngày</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input type="text" class="form-control" value="{{ $manufacturerNote->date ?? date('d/m/Y') }}" name="manufacturerNote[date]" required>
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
                            <th class="col-md-5 text-center">Phôi</th>
							<th class="col-md-1 text-center">Số lượng</th>
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
                        <a href="{{ url('manufacturer-note') }}" class="btn btn-danger cancel">Hủy</a>
                    </div>
                </div>
			</div>
			<!-- /.box -->
		</form>
	</section>
@endsection

@section('javascript')
    <script>
        $(function () {

            function maskDate(obj) {
                obj.inputmask({
                    alias: 'date',
                    displayFormat: 'dd/mm/yyyy',
                });
            }
            
            let date = $('[name="manufacturerNote[date]"]');
            
            maskDate(date);
            
            function addSelect2Contract(el) {
                el.select2({
                    placeholder: 'Nhập số LSX',
                    minimumInputLength: 1,
                    ajax: {
                        url: '{{ route('contract.getManufacturerOrder') }}',
                        delay: 200,
                        dataType: 'json',
                        processResults: function (data) {
                            return {
                                results: $.map(data, function (item) {
                                    return {
                                        text: item.name,
                                        id: item.id,
                                        manufacturerNumber: item.number,
                                        product_id: item.product_id,
                                    }
                                })
                            };
                        },
                        cache: true
                    },
                });
            }

            function addSelect2Bom(el) {
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
            }

            let contract_detail = $('[name$="[contract_detail_id]"]');
            // addSelect2Contract(contract_detail);
            getProduct(contract_detail);

            function getProduct(el) {
                el.on('select2:select', function (e) {
                    let data = e.params.data;
                    $(this).parents('tr').find('input[name$="[manufacturer_order_number]"]').val(data.manufacturerNumber);
                    $(this).parents('tr').find('input[name$="[product_id]"]').val(data.product_id);

                    let bomIdElement = $(this).parents('tr').find('select[name$="[bom_id]"]');
                    addSelect2Bom(bomIdElement);
                });
            }
    
            function updateNumberOfRow() {
                let rows = $('tr[data-key]');
                rows.each(function (i, row) {
                    $(row).attr('data-key', i);
                    $(row).children('[data-col-seq="0"]').find('span').text(i + 1);
                    $(row).children('[data-col-seq="1"]').find('input').attr('name', 'contract_details[' + (i) + '][code]');
                    $(row).children('[data-col-seq="2"]').find('select').attr('name', 'contract_details[' + (i) + '][price_id]');
                    $(row).children('[data-col-seq="3"]').find('input').attr('name', 'contract_details[' + (i) + '][quantity]');
                    $(row).children('[data-col-seq="4"]').find('input').attr('name', 'contract_details[' + (i) + '][selling_price]');
                    $(row).children('[data-col-seq="5"]').find('input').attr('name', 'contract_details[' + (i) + '][deadline]');
                    $(row).children('[data-col-seq="6"]').find('select').attr('name', 'contract_details[' + (i) + '][supplier_id]');
                    $(row).children('[data-col-seq="7"]').find('input').attr('name', 'contract_details[' + (i) + '][note]');
                    
                    if (i === 0) {
                        if (rows.length === 1) {
                            $(row).find('button.removeRow').addClass('hidden');
                        } else {
                            $(row).find('button.removeRow').removeClass('hidden');
                        }
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
                let select2 = newRow.find('.select2.price');
        
                newRow.attr('data-key', numberOfProduct);
                newRow.children('[data-col-seq="0"]').find('span').text(numberOfProduct + 1);
                newRow.children('[data-col-seq="1"]').find('input').attr('name', 'contract_details[' + (numberOfProduct) + '][code]');
                newRow.children('[data-col-seq="2"]').find('select').attr('name', 'contract_details[' + (numberOfProduct) + '][price_id]');
                newRow.children('[data-col-seq="3"]').find('input').attr('name', 'contract_details[' + (numberOfProduct) + '][quantity]');
                newRow.children('[data-col-seq="4"]').find('input').attr('name', 'contract_details[' + (numberOfProduct) + '][selling_price]');
                newRow.children('[data-col-seq="5"]').find('input').attr('name', 'contract_details[' + (numberOfProduct) + '][deadline]');
                newRow.children('[data-col-seq="6"]').find('select').attr('name', 'contract_details[' + (numberOfProduct) + '][supplier_id]');
                newRow.children('[data-col-seq="7"]').find('input').attr('name', 'contract_details[' + (numberOfProduct) + '][note]');
                lastRow.find('button.removeRow').removeClass('hidden');
                newRow.find('button.removeRow').removeClass('hidden');
                newRow.find('.select2-container').remove();
                newRow.children('[data-col-seq="2"]').find('option').remove();
                newRow.find('input').val('');
                tableBody.append(newRow);
        
                addSelect2(select2);
                getPrice(select2);
                maskCurrency(newRow.find('[name$="[selling_price]"]'));
                maskDate(newRow.find('[name$="[deadline]"]'));
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
                convertDateToTimestamp($('[name="manufacturerNote[date]"]'));
            });
        });
    </script>
@show