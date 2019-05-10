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

				<div class="box-body">
                    <div class="form-group">
                        <label class="col-md-2 control-label">Đơn vị</label>
                        <select class="form-control col-md-10" name="customer_id" required>
                            <option value="">--Lựa chọn đơn vị--</option>
                            @foreach ($customers as $customer)
                                @if (isset($profitRate) && $profitRate->customer_id===$customer->id)
                                    <option value="{{ $customer->id }}" selected>{{ $customer->name }}</option>
                                @else
                                    <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Nhóm</label>
                        <select class="form-control col-md-10" name="category_id" required>
                            <option value="">--Lựa chọn nhóm--</option>
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
                        <label class="col-md-2 control-label">Tỉ lệ</label>
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
            let rate = $('.input-mask');
            rate.inputmask({
                'mask'  : "9,999"
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