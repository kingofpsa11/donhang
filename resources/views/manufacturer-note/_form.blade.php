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
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Số phiếu sản xuất</label>
                                <input type="text" class="form-control" name="manufacturerNote[number]" required value="{{ $manufacturerNote->number ?? '' }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Ngày</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input type="text" class="form-control" value="@yield('date')" name="manufacturerNote[date]" required>
                                </div>
                                <!-- /.input group -->
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Đơn vị</label>
                                <input type="text" class="form-control" name="manufacturerNote[number]" required>
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
							<th class="col-md-1">Số LSX</th>
							<th class="col-md-5">Tên sản phẩm</th>
                            <th class="col-md-3">Định mức</th>
							<th class="col-md-1">Số lượng</th>
							<th class="col-md-2">Ghi chú</th>
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
                                    }
                                })
                            };
                        },
                        cache: true
                    },
                });
            }

            function addSelect2Bom(el) {
                let bomId = contract_detail.val();
                el.select2({
                    placeholder: 'Nhập số LSX',
                    minimumInputLength: 1,
                    ajax: {
                        url: '{{ route('bom.getBom') }}',
                        delay: 200,
                        data: function(params) {
                            return {
                                search: params.term,
                                bomId: bomId,
                            }
                        },
                        dataType: 'json',
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

            let contract_detail = $('[name$="[contract_detail_id]"]');
            addSelect2Contract(contract_detail);

            contract_detail.on('select2:select', function () {
               addSelect2Bom($('[name$="bom_id"]'));
            });

            function getProduct(el) {
                el.on('select2:select', function (e) {
                    let data = e.params.data;
                    $(this).parents('tr').find('input[name$="[code]"]').val(data.code);
                    $(this).parents('tr').find('input[name$="[contract_id]"]').val(data.number);
                    $(this).parents('tr').find('input[name$="[quantity]"]').val(data.quantity);
                });
            }
            
            function updateNumberOfRow() {
                let rows = $('tr[data-key]');
                
                rows.each(function (i, row) {
                    $(row).attr('data-key', i);
                    $(row).children('[data-col-seq="0"]').text(i + 1);
                    $(row).children('[data-col-seq="1"]').find('input').attr('name', 'outputOrderDetails[' + i + '][contract_id]');
                    $(row).children('[data-col-seq="2"]').find('input').attr('name', 'outputOrderDetails[' + i + '][manufacturer_order_number]');
                    $(row).children('[data-col-seq="3"]').find('select').attr('name', 'outputOrderDetails[' + i + '][contract_detail_id]');
                    $(row).children('[data-col-seq="4"]').find('input').attr('name', 'outputOrderDetails[' + i + '][quantity]');
                    $(row).children('[data-col-seq="5"]').find('input').attr('name', 'outputOrderDetails[' + i + '][note]');
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
                let select2 = newRow.find('.select2.contract');

                newRow.attr('data-key', numberOfProduct);
                newRow.children('[data-col-seq="0"]').text(numberOfProduct + 1);
                newRow.children('[data-col-seq="1"]').find('input').attr('name', 'outputOrderDetails[' + numberOfProduct + '][contract_id]');
                newRow.children('[data-col-seq="2"]').find('input').attr('name', 'outputOrderDetails[' + numberOfProduct + '][manufacturer_order_number]');
                newRow.children('[data-col-seq="3"]').find('select').attr('name', 'outputOrderDetails[' + numberOfProduct + '][contract_detail_id]');
                newRow.children('[data-col-seq="4"]').find('input').attr('name', 'outputOrderDetails[' + numberOfProduct + '][quantity]');
                newRow.children('[data-col-seq="5"]').find('input').attr('name', 'outputOrderDetails[' + numberOfProduct + '][note]');
                lastRow.find('button.removeRow').removeClass('hidden');
                newRow.find('button.removeRow').removeClass('hidden');
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
                });
            }
            
            $('#form').on('submit', function () {
                convertDateToTimestamp($('[name="outputOrder[date]"]'));
            });

@show