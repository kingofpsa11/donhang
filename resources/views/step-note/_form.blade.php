@extends('layouts.dashboard')

@section('title', 'Phiếu công đoạn')

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
                                <input type="text" class="form-control" name="number" value="{{ $stepNote->number ?? '' }}" required>
                            </div>
                        </div>
						<div class="col-md-4">
							<div class="form-group">
								<label>Ngày</label>
								<div class="input-group">
									<div class="input-group-addon">
										<i class="fa fa-calendar"></i>
									</div>
									<input type="text" class="form-control"  name="date" value="{{ $stepNote->date ?? date('d/m/Y') }}" required>
								</div>
								<!-- /.input group -->
							</div>
						</div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Công đoạn</label>
                                <select name="step_id" class="form-control" id="step_id">
                                    <option value="" hidden="">Chọn công đoạn</option>
                                    @foreach($steps as $step)
                                        @if (isset($stepNote) && $stepNote->step_id === $step->id)
                                            <option value="{{ $stepNote->step_id }}" selected>{{ $stepNote->step->name }}</option>
                                        @else
                                            <option value="{{ $step->id }}">{{ $step->name }}</option>
                                        @endif
                                    @endforeach

                                </select>
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
							<th class="col-md-1">Số LSX</th>
							<th class="col-md-2">Mã sản phẩm</th>
							<th class="col-md-6">Tên sản phẩm</th>
							<th class="col-md-3">Số lượng</th>
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
                        <a href="{{ route('step-notes.index') }}" class="btn btn-danger cancel">Hủy</a>
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

            let date = $('[name="date"]');

            maskDate(date);

            function addSelect2(el) {
                let step_id = $('#step_id').val();
                el.select2({
                    placeholder: 'Nhập số lệnh sản xuất',
                    ajax: {
                        url: '{{ route('manufacturer-notes.get_by_step') }}',
                        data: function (params) {
                            return {
                                search: params.term,
                                stepId: step_id,
                            };
                        },
                        dataType: 'json',
                        processResults: function (data) {
                            return {
                                results: $.map(data, function (item) {
                                    return {
                                        text: item.name,
                                        id: item.product_id,
                                        number: item.number,
                                        code: item.code,
                                        quantity: item.remain_quantity,
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
            }

            function getProduct(el) {
                el.on('select2:select', function (e) {
                    let data = e.params.data;
                    $(this).parents('tr').find('input[name*="code"]').val(data.code);
                    $(this).parents('tr').find('input[name*="manufacturer_order_number"]').val(data.number);
                    $(this).parents('tr').find('input[name*="contract_detail_id"]').val(data.contract_detail_id);
                    $(this).parents('tr').find('input[name*="quantity"]').val(data.quantity);
                });
            }

            let product_id = $('#product_id');
            $('#step_id').on('change', function () {
                $('.addRow').removeClass('disabled');
                addSelect2(product_id);
                getProduct(product_id);
            });

            addSelect2(product_id);

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
                let select2 = newRow.find('[name*=product_id]');

                newRow.attr('data-key', numberOfProduct);
                newRow.children('[data-col-seq="0"]').find('span').text(numberOfProduct + 1);
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
        });
    </script>
@stop