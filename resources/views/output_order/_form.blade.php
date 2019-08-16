@extends('layouts.dashboard')

@section('title', 'Lệnh xuất hàng')

@section('content')
	
	<!-- Main content -->
	<section class="content container-fluid">
		<form action=@yield('route') method="POST" id="form">
			@csrf
			@yield('method')
			<div class="box box-default">
				<div class="box-header with-border">
                    <div class="form-group">
                        <label for="">Đơn vị nhận hàng</label>
                        <select class="form-control select2 customer" name="customer_id" style="width: 100%;" required>
                            @if (isset($outputOrder))
                                <option value="{{ $outputOrder->customer_id }}">{{ $outputOrder->outputOrderDetails->first()->contractDetail->contract->customer->name }}</option>
                            @endif
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Người nhận hàng</label>
                                <input type="text" class="form-control" name="customer_user" value="{{ $outputOrder->customer_user ?? '' }}">
                            </div>
                        </div>
						<div class="col-md-4">
							<div class="form-group">
								<label>Số lệnh xuất hàng</label>
								<input type="text" class="form-control" name="number" required value="{{ $outputOrder->number ?? '' }}">
                                <span class="check-number text-red"></span>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label>Ngày xuất hàng</label>
								<div class="input-group">
									<div class="input-group-addon">
										<i class="fa fa-calendar"></i>
									</div>
									<input type="text" class="form-control"  name="date" value="{{ $outputOrder->date ?? date('d/m/Y') }}" required>
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
							<th>Số đơn hàng</th>
							<th>Số LSX</th>
							<th>Mã sản phẩm</th>
							<th class="col-md-6">Tên sản phẩm</th>
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
                        <button class="btn btn-primary addRow
                            @if (Request::is('*/create'))
                                disabled
                            @endif
                        ">Thêm dòng</button>
                        <input type="submit" value="Lưu" class="btn btn-success save">
                        <a href="{{ route('output-orders.index') }}" class="btn btn-danger cancel">Hủy</a>
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
            let customerSelect = $('.select2.customer');
            customerSelect.select2({
                placeholder: 'Nhập khách hàng',
                minimumInputLength: 1,
                ajax: {
                    url: '{{ route('customer.listCustomer') }}',
                    delay: 200,
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
                    placeholder: 'Nhập số đơn hàng',
                    minimumInputLength: 1,
                    ajax: {
                        url: '{{ route('contract.shows') }}',
                        delay: 200,
                        data: function (params) {
                            return {
                                search: params.term,
                                customer_id: customerSelect.val()
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
                                        code: item.code,
                                        remain_quantity: item.remain_quantity,
                                        manufacturer_number: item.manufacturer_number
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
                        return $(`<div class="container-fluid"><div class="row"><div class="col-md-8">${repo.text}</div><div class="col-md-2">${repo.number}</div><div class="col-md-2">${repo.remain_quantity}</div></div></div> `);
                    },
                });
            }

            function getProduct(el) {
                el.on('select2:select', function (e) {
                    let data = e.params.data;
                    $(this).parents('tr').find('input[name*="code"]').val(data.code);
                    $(this).parents('tr').find('.code').text(data.code);
                    $(this).parents('tr').find('.manufacturer-order-number').text(data.manufacturer_number);
                    $(this).parents('tr').find('.contract-number').text(data.number);
                    $(this).parents('tr').find('input[name*="quantity"]').val(data.remain_quantity);
                });
            }

            let contractSelect = $('.select2.contract');
            addSelect2(contractSelect);
            getProduct(contractSelect);

            customerSelect.on('select2:select', function () {
                $('.addRow').removeClass('disabled');
            });

            function updateNumberOfRow() {
                let rows = $('tr[data-key]');

                rows.each(function (i, row) {
                    $(row).attr('data-key', i);
                    $(row).children('[data-col-seq="0"]').find('span').text(i + 1);
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
            $('.box-footer').on('click', '.addRow:not(".disabled")', function (e) {
                e.preventDefault();
                let tableBody = $('tbody');
                let numberOfProduct = tableBody.children().length;
                let lastRow = $('tr:last');
                let newRow = lastRow.clone();
                let select2 = newRow.find('.select2.contract');

                newRow.attr('data-key', numberOfProduct);
                newRow.children('[data-col-seq="0"]').text(numberOfProduct + 1);
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

            $('#form').on('submit', function (e) {
                e.preventDefault();
                let form = this;
                let number = $('[name*="number"]').val();
                let customer_id = customerSelect.val();
                let year = $('[name*="date"]').val().split('/')[2];

                $.get(
                    "{{ route('output-orders.exist_number') }}",
                    {number: number, customer_id: customer_id, year: year},
                    function (result) {
                        if (result > 0 && window.location.pathname.indexOf('create') >= 0) {
                            $('[name="number"]').parent().find('span').html('Đã tồn tại số lệnh');
                        } else {
                            form.submit();
                        }
                    },
                    "text"
                );
            });
        });
    </script>
@stop