@extends('layouts.dashboard')

@section('title', 'Tỉ lệ điều chỉnh giá')

@section('content')
	
	<!-- Main content -->
	<section class="content container-fluid text-center">
		<form action=@yield('route') method="POST" id="form" class="col-md-6 col-md-offset-3">
			@csrf
			@yield('method')
			<div class="box box-primary">
				<div class="box-header with-border">
					<h3 class="box-title">Tỉ lệ điều chỉnh giá</h3>
				</div>
				<!-- /.box-header -->

				<div class="box-body text-left">
                    <div class="form-group text-left">
                        <label class="control-label">Đơn vị</label>
                        <select class="form-control" name="customer_id" id="customer_id" required>
                            <option value="">--Lựa chọn đơn vị--</option>
                            @foreach ($customers as $customer)
                                @if (isset($profitRate) && $profitRate->customer_id===$customer->id)
                                    <option value="{{ $customer->id }}" selected>{{ $customer->name }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Nhóm</label>
                        <select class="form-control" name="category_id" required>
                            <option hidden>--Lựa chọn nhóm--</option>
                            @foreach ($categories as $category)
                                @if (isset($profitRate) && $profitRate->category_id===$category->id)
                                    <option value="{{ $category->id }}" selected>{{ $category->name }}</option>
                                @else
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Tỉ lệ</label>
                        <input type="text" class="form-control input-mask" value="{{ $profitRate->rate ?? ''}}" name="rate" required>
                    </div>
                        <!-- /.input group -->
				</div>
				<!-- /.box-body -->
				<div class="box-footer text-right">
                    <input type="submit" value="Lưu" class="btn btn-success">
                    <a href="{{ route('profit-rate.index') }}" class="btn btn-warning">Hủy</a>
				</div>
			</div>
		</form>
	</section>
@endsection

@section('javascript')
    <script>
        $( document ).ready(function() {

            $('#customer_id').select2({
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

            let rate = $('.input-mask');
            rate.inputmask('numeric',{
                // mask    : '9,999',
                radixPoint  : ',',
                placeholder : '0.000',
                digits  : '3',
                removeMaskOnSubmit: true,
                unmaskAsNumber: true,
            });

            $('form').on('submit', function (e) {
                if (!rate.inputmask('isComplete')) {
                    e.preventDefault();
                    rate.focus();
                }
            })
        });
    </script>
@stop