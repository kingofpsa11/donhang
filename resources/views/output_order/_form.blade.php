@extends('layouts.dashboard')

@section('title', 'Lệnh xuất hàng')

@section('action', 'Tạo LXH')

@section('content')
	
	<!-- Main content -->
	<section class="content container-fluid">
		<form action=@yield('route') method="POST" id="form">
			@csrf
			@yield('method')
			<div class="box box-default">
				<div class="box-header with-border">
					<h3 class="box-title">Lệnh xuất hàng</h3>
					
					<div class="box-tools pull-right">
						<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
					</div>
				</div>
				<!-- /.box-header -->
				
				<div class="box-body">
					<div class="row">
						<div class="col-md-3">
							<div class="form-group">
								<label>Đơn vị xuất hàng</label>
								<select class="form-control input-sm select2 customer" name="outputOrder[customer_id]" required>
                                    @yield('customer')
								</select>
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label>Số lệnh xuất hàng</label>
								<input type="text" class="form-control input-sm" name="outputOrder[number]" required>
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label>Ngày xuất hàng</label>
								<div class="input-group">
									<div class="input-group-addon">
										<i class="fa fa-calendar"></i>
									</div>
									<input type="text" class="form-control input-sm" value="@yield('output-order-date')" name="outputOrder[date]" required>
								</div>
								<!-- /.input group -->
							</div>
						</div>
					</div>
					<!-- /.row -->
				</div>
				<!-- /.box-body -->
				<div class="box-footer">
				</div>
			</div>
			
			<div class="box">
				<div class="box-header">
					<h3 class="box-title">Nội dung</h3>
				</div>
				<!-- /.box-header -->
				<div class="box-body table-responsive">
					<table id="example1" class="table table-bordered table-striped table-condensed">
						<thead>
						<tr>
							<th>STT</th>
							<th>Số đơn hàng</th>
							<th>Số LSX</th>
							<th>Mã sản phẩm</th>
							<th>Tên sản phẩm</th>
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
                <div class="box-footer">
                    <div class="col-md-4 pull-right">
                        <button class="btn btn-primary col-md-3 addRow
                            @if (Request::is('*/create'))
                                disabled
                            @endif
                        ">Thêm dòng</button>
                        <input type="submit" value="Lưu" class="btn btn-success save col-md-3">
                        <a href="{{ url('output-order') }}" class="btn btn-danger col-md-3 cancel">Hủy</a>
                        <button class="btn btn-default col-md-3 print">In</button>
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

            let customerSelect = $('.select2.customer');
            customerSelect.select2();
            
            function maskDate(obj) {
                obj.inputmask({
                    alias: 'date',
                    displayFormat: 'dd/mm/yyyy',
                });
            }
            
            let date = $('[name="outputOrder[date]"]');
            
            maskDate(date);
            
            function addSelect2(el) {
                el.select2({
                    placeholder: 'Nhập số đơn hàng',
                    minimumInputLength: 1,
                    ajax: {
                        url: '{{ route('contract.shows') }}',
                        delay: 200,
                        data: function (params) {
                            let query = {
                                search: params.term,
                                customer_id: customerSelect.val()
                            };

                            return query;
                        },
                        dataType: 'json',
                        processResults: function (data) {
                            return {
                                results: $.map(data, function (item) {
                                    return {
                                        text: item.name,
                                        id: item.id,
                                        number: item.number,
                                        code: item.code,
                                        quantity: item.quantity
                                    }
                                })
                            };
                        },
                        cache: true
                    },
                });
            }

            function getProduct(el) {
                el.on('select2:select', function (e) {
                    let data = e.params.data;
                    $(this).parents('tr').find('input[name$="[code]"]').val(data.code);
                    $(this).parents('tr').find('input[name$="[contract_id]"]').val(data.number);
                    $(this).parents('tr').find('input[name$="[quantity]"]').val(data.quantity);
                });
            }
            
            let contractSelect = $('.select2.contract');

            customerSelect.on('select2:select', function () {
                $('.addRow').removeClass('disabled');
                addSelect2(contractSelect);
                getProduct(contractSelect);
            });

            function updateNumberOfRow() {
                let rows = $('tr[data-key]');
                rows.each(function (i, row) {
                    $(row).attr('data-key', i);
                    $(row).children('[data-col-seq="0"]').text(i + 1);
                    $(row).children('[data-col-seq="1"]').find('input').attr('name', 'outputOrderDetails[' + i + '][contract_id]');
                    $(row).children('[data-col-seq="2"]').find('input').attr('name', 'outputOrderDetails[' + i + '][manufacturer_order_number]');
                    $(row).children('[data-col-seq="3"]').find('input').attr('name', 'outputOrderDetails[' + i + '][code]');
                    $(row).children('[data-col-seq="4"]').find('select').attr('name', 'outputOrderDetails[' + i + '][contract_detail_id]');
                    $(row).children('[data-col-seq="5"]').find('input').attr('name', 'outputOrderDetails[' + i + '][quantity]');
                    $(row).children('[data-col-seq="6"]').find('input').attr('name', 'outputOrderDetails[' + i + '][note]');
                });
            }
            
            //Add or remove row to table
            $('.box-footer').on('click', '.addRow:not(".disabled")',function (e) {
                e.preventDefault();
                let tableBody = $('tbody');
                let numberOfProduct = tableBody.children().length;
                let lastRow = $('tr:last');
                let newRow = lastRow.clone();
                let select2 = newRow.find('.select2.contract');

                newRow.attr('data-key', numberOfProduct);
                newRow.children('[data-col-seq="0"]').text(numberOfProduct + 1);
                newRow.children('[data-col-seq="1"]').find('input').attr('name', 'outputOrderDetails[' + numberOfProduct + '][contract_id]');
                newRow.children('[data-col-seq="2"]').find('input').attr('name', 'outputOrderDetails[' + numberOfProduct + '][manufacturer_order_number]');
                newRow.children('[data-col-seq="3"]').find('input').attr('name', 'outputOrderDetails[' + numberOfProduct + '][code]');
                newRow.children('[data-col-seq="4"]').find('select').attr('name', 'outputOrderDetails[' + numberOfProduct + '][contract_detail_id]');
                newRow.children('[data-col-seq="5"]').find('input').attr('name', 'outputOrderDetails[' + numberOfProduct + '][quantity]');
                newRow.children('[data-col-seq="6"]').find('input').attr('name', 'outputOrderDetails[' + numberOfProduct + '][note]');
                newRow.find('.select2-container').remove();
                newRow.find('option').remove();
                newRow.find('input').val('');
                tableBody.append(newRow);

                addSelect2(select2);
                getProduct(select2);
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

            function convertDateToTimestamp(obj) {
                obj.each(function (i, el) {
                    let date = $(el).val();
                    $(el).inputmask('remove');
                    let datePart = date.split('/');
                    let newDate = new Date(datePart[2], datePart[1] - 1, datePart[0]);
                    $(el).val(newDate.getTime()/1000);
                })
            }
            
            $('#form').on('submit', function () {
                convertDateToTimestamp($('[name="outputOrder[date]"]'));
            })


        });
    </script>
@stop