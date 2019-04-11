@extends('layouts.app')

@section('title', 'Hapulico')

@section('content')
    <section class="content-header">
        <h1>
            Đơn hàng
            <small>Tạo đơn hàng</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
            <li class="active">Here</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content container-fluid">
        <form action="POST">
            <div class="box box-default">
                <div class="box-header with-border">
                    <h3 class="box-title">Đơn hàng</h3>

                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
                    </div>
                </div>
                <!-- /.box-header -->

                <div class="box-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Đơn vị đặt hàng</label>
                                <select class="form-control select2" style="width: 100%;">
                                    <option>--Lựa chọn đơn vị đặt hàng--</option>
                                    @foreach ($customers as $customer)
                                        <option value="{{ $customer->id }}">{{ $customer->short_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <!-- /.col -->
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Số đơn hàng</label>
                                <input type="text" class="form-control" placeholder="Nhập số đơn hàng ...">
                            </div>
                        </div>
                        <!-- /.col -->
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Ngày đặt hàng</label>

                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input type="text" class="form-control" data-inputmask="'alias': 'dd/mm/yyyy'" data-mask name="date">
                                </div>
                                <!-- /.input group -->
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">

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
                <div class="box-body">
                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>STT</th>
                            <th>Tên sản phẩm</th>
                            <th>Số lượng</th>
                            <th>Đơn giá</th>
                            <th>Tiến độ</th>
                            <th>Ghi chú</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr data-key="0">
                            <td class="col-md-1" data-col-seq="0">1</td>
                            <td class="col-md-4" data-col-seq="1">
                                <div class="form-group">
                                    <input type="text" class="form-control" name="product[0][name]">
                                </div>
                            </td>
                            <td class="col-md-1" data-col-seq="2">
                                <div class="form-group">
                                    <input type="number" class="form-control" name="product[0][quantity]">
                                </div>
                            </td>
                            <td class="col-md-2" data-col-seq="3">
                                <div class="form-group">
                                    <input type="text" class="form-control" name="product[0][price]" disabled>
                                </div>
                            </td>
                            <td class="col-md-2" data-col-seq="4">
                                <div class="form-group">
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                        <input type="text" class="form-control" data-inputmask="'alias': 'dd/mm/yyyy'" data-mask name="product[0][deadline]">
                                    </div>
                                </div>
                            </td>
                            <td class="col-md-2" data-col-seq="5">
                                <input type="text" class="form-control" name="product[0][note]">
                            </td>
                            <td data-col-seq="6">
                                <button class="btn btn-primary addProduct"><i class="fa fa-plus-square" aria-hidden="true"></i></button>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <div class="form-group">
                        <div class="col-md-2 pull-right">
                            <input type="submit" value="Lưu" class="btn btn-success">
                            <button class="btn btn-danger">Hủy</button>
                        </div>
                    </div>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </form>
    </section>
@endsection

@section('javascript')
    <script>
        $('.select2').select2();
        $('[data-mask]').inputmask();
        // $('#example1').DataTable();
        $('#example1').on('click', '.addProduct', function (e) {
            e.preventDefault();
            let numberOfProduct = $('tbody').children().length + 1;
            let product = $('tr[data-key="0"]').clone();
            product.attr('data-key', numberOfProduct);
            product.children('[data-col-seq="0"]').text(numberOfProduct);
            product.children('[data-col-seq="1"]').find('input').attr('name', 'product[' + (numberOfProduct - 1) + '][name]');
            product.children('[data-col-seq="2"]').find('input').attr('name', 'product[' + (numberOfProduct - 1) + '][quantity]');
            product.children('[data-col-seq="3"]').find('input').attr('name', 'product[' + (numberOfProduct - 1) + '][price]');
            product.children('[data-col-seq="4"]').find('input').attr('name', 'product[' + (numberOfProduct - 1) + '][deadline]');
            product.children('[data-col-seq="5"]').find('input').attr('name', 'product[' + (numberOfProduct - 1) + '][note]');
            $('tbody').append(product);
        });
    </script>
@stop