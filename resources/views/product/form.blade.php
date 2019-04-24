@extends('layouts.dashboard')

@section('title', 'Đơn hàng')

@section('content')
    <section class="content-header">
        <h1>
            Tạo sản phẩm
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('product.index') }}"><i class="fa fa-dashboard"></i> Danh mục sản phẩm</a></li>
            <li class="active">Tạo sản phẩm</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <form action="@yield('route')" method="POST" id="form">
                    @csrf
                    @yield('method')
                    <div class="box box-primary">
                        <div class="box-header">
                            <h3 class="box-title">Thêm sản phẩm</h3>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            @yield('form-content')
                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer">
                            <button type="submit" class="btn btn-success btn col-md-2">Lưu</button>
                            <a href="{{ route('product.index') }}" class="btn btn-danger btn col-md-2">Hủy</a>
                        </div>
                    </div>
                    <!-- /.box -->
                </form>
            </div>
        </div>
    </section>
@endsection

@section('javascript')
    <script>

        //Click cancel button
        $('button.cancel').on('click', function (e) {
            e.preventDefault();
        });

    </script>
@stop