@extends('layouts.dashboard')

@section('title', 'Khách hàng')

@section('content')

    @php( $view = isset($view) ? $view : '' )
    
    <!-- Main content -->
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <form action="@yield('route')" method="POST" id="form" enctype="multipart/form-data">
                    @csrf
                    @yield('method')
                    <div class="box box-primary">
                        <div class="box-header">
                            <h3 class="box-title">Khách hàng mới</h3>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <div class="form-group">
                                <label for="" class="control-label">Mã khách</label>
                                <input type="text" class="form-control" name="code" value="{{ $customer->code ?? '' }}" {{ $view }}>
                            </div>
                            <div class="form-group">
                                <label for="" class="control-label">Tên khách hàng</label>
                                <input type="text" class="form-control" name="name" value="{{ $customer->name ?? '' }}" {{ $view }}>
                            </div>
                            <div class="form-group">
                                <label for="" class="control-label">Tên rút gọn</label>
                                <input type="text" class="form-control" name="short_name" value="{{ $customer->short_name ?? '' }}" {{ $view }}>
                            </div>
                            <div class="form-group">
                                <label for="" class="control-label">Địa chỉ</label>
                                <input type="text" class="form-control" name="address" value="{{ $customer->address ?? '' }}" {{ $view }}>
                            </div>
                            <div class="form-group">
                                <label for="" class="control-label">Mã số thuế</label>
                                <input id="" class="form-control" name="tax_registration_number" value="{{ $customer->tax_registration_number ?? '' }}" {{ $view }}>
                                <span class="check text-red"></span>
                            </div>
                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer">
                            @if (Request::route()->getAction() === 'show')
                                <a href="{{ route('customers.create') }}" class="btn btn-success">Tạo mới</a>
                                <a href="{{ route('customers.edit', $customer) }}" class="btn btn-primary">Sửa</a>
                                <button class="btn btn-danger" data-toggle="modal" data-target="#modal">Xóa</button>
                            @else
                                <input type="submit" class="btn btn-success btn" value="Lưu">
                                <input type="reset" value="Hủy" class="btn btn-warning">
                            @endif
                        </div>
                    </div>
                    <!-- /.box -->
                </form>
            </div>
        </div>
    </section>
    @if (Request::route()->getAction() === 'show')
        @include('shared._modal', [
            'model' => $customer,
            'modelName' => 'khách hàng',
            'modelInformation' => $customer->name,
            'routeName' => 'customers'
        ])
    @endif
    
@endsection

@section('javascript')
    <script>
        $('#form').on('submit', function (e) {
            e.preventDefault();
            let form = this;
            let taxObj = $('[name*="tax_registration_number"]');
            let tax = taxObj.val();
            taxObj.parent().find('span').html('');
        
            $.get(
                "{{ route('customers.exist_tax') }}",
                {tax: tax},
                function (result) {
                    if (result > 0 && window.location.pathname.indexOf('create') >= 0) {
                        taxObj.parent().find('span').html('Đã tồn tại Khách hàng');
                    } else {
                        form.submit();
                    }
                },
                "text"
            );
        });
    </script>
@stop