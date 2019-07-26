@extends('layouts.dashboard')

@section('title', 'Đơn hàng')


@section('content')
    <section class="content-header">
        <h1>
            Tạo sản phẩm
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('products.index') }}"><i class="fa fa-dashboard"></i> Danh mục sản phẩm</a></li>
            <li class="active">Tạo sản phẩm</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Thêm sản phẩm</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div class="form-group">
                            <label for="" class="col-md-3 control-label">Nhóm</label>
                            <input type="text" class="form-control" value="{{ $product->category->name }}" readonly>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-md-3 control-label">Mã sản phẩm</label>
                            <input type="text" class="form-control" value="{{ $product->code }}" readonly>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-md-3 control-label">Tên sản phẩm</label>
                            <input type="text" class="form-control" value="{{ $product->name }}" readonly>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-md-3 control-label">Ghi chú</label>
                            <textarea id="" class="form-control" value="{{ $product->note }}" readonly></textarea>
                        </div>
                        <div class="form-group">
                            @if (isset($product->file))
                                @foreach (json_decode($product->file) as $file)
                                    <div class="btn btn-default">
                                        <a href="{{ asset('storage/' . $file) }}">
                                            {{ $file }}
                                        </a>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                        <div class="box-footer">
                            <a href="{{ route('products.edit', [$product]) }}" class="btn btn-danger">Sửa</a>
                        </div>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>
        </div>
    </section>
@endsection

@section('javascript')
    <script src="{{ asset('plugins/input-mask/jquery.inputmask.numeric.extensions.js') }}"></script>
    <script>
        $('[data-mask]').inputmask();

        let customerSelect = $('.select2.customer');
        customerSelect.select2();

        $('#contract-show').DataTable({
            'paging'        : false,
            'lengthChange'  : false,
            'info'          : false,
            searching       : false,
            ordering        : false,
            columnDefs: [
                {
                    targets: [ 2 ],
                    render: $.fn.dataTable.render.number('.', ','),
                    className   : 'dt-body-right'
                },
                {
                    targets: [ 3 ],
                    className   : 'dt-body-right'
                }
            ]
        });
        $('button.cancel').on('click', function (e) {
            e.preventDefault();
        })
    </script>
@stop