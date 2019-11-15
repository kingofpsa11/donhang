@extends('layouts.dashboard')

@section('title', 'Định mức')

@section('content')

    <!-- Main content -->
    <div class="box">
        <div class="box-header">
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="" class="control-label">Tên sản phẩm</label>
                        <input name="product_id" id="product_id" class="form-control" readonly value="{{ $poleWeight->product->name }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="" class="control-label">Nhóm sản phẩm</label>
                        <input name="expense_of_pole_id" id="expense_of_pole_id" class="form-control" readonly value="{{ $poleWeight->expenseOfPole->name }}">
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="form-group">
                        <label for="" class="control-label">Tỷ lệ nhân công</label>
                        <input type="text" name="ty_le_nhan_cong" id="ty_le_nhan_cong" class="form-control decimal" readonly value="{{ $poleWeight->ty_le_nhan_cong }}">
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="form-group">
                        <label for="" class="control-label">Diện tích</label>
                        <input type="text" name="area" id="area" class="form-control decimal" readonly value="{{ $poleWeight->area }}">
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="form-group">
                        <label for="" class="control-label">Khối lượng</label>
                        <input type="text" name="weight" id="weight" class="form-control decimal" readonly value="{{ $poleWeight->weight }}">
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="form-group">
                        <label for="" class="control-label">Đơn giá</label>
                        <input type="text" name="unit_price" id="unit_price" class="form-control number" readonly value="{{ $poleWeight->unit_price }}">
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="form-group">
                        <label for="" class="control-label">Thành tiền</label>
                        <input type="text" name="price" id="price" class="form-control number" readonly value="{{ $poleWeight->price }}">
                    </div>
                </div>
            </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body table-responsive">
            <table id="table" class="table table-bordered table-striped table-condensed">
                <thead>
                <tr>
                    <th>STT</th>
                    <th>Tên chi tiết</th>
                    <th>Chủng loại</th>
                    <th>Số lượng</th>
                    <th>D ngọn/D ngoài</th>
                    <th>D gốc/D trong</th>
                    <th>Chiều dày</th>
                    <th>Chiều cao</th>
                    <th>Chiều dài</th>
                    <th>Chiều rộng</th>
                    <th>Diện tích</th>
                    <th>Khối lượng</th>
                </tr>
                </thead>
                <tbody>
                    @foreach ($poleWeight->poleWeightDetails as $detail)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $detail->name }}</td>
                            <td>{{ $detail->shape }}</td>
                            <td>{{ $detail->quantity }}</td>
                            <td>{{ $detail->d_ngon }}</td>
                            <td>{{ $detail->d_goc }}</td>
                            <td>{{ $detail->day }}</td>
                            <td>{{ $detail->chieu_cao }}</td>
                            <td>{{ $detail->chieu_dai }}</td>
                            <td>{{ $detail->chieu_rong }}</td>
                            <td>{{ $detail->dien_tich }}</td>
                            <td>{{ $detail->khoi_luong }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <!-- /.box-body -->
        <div class="box-footer text-right">
            <div>
                <a href="{{ route('pole-weight.create') }}" class="btn btn-success">Tạo mới</a>
                <a href="{{ route('pole-weight.edit', $poleWeight) }}" class="btn btn-primary">Sửa</a>
                <button class="btn btn-danger" id="delete" data-toggle="modal" data-target="#modal">Xóa</button>
            </div>
        </div>
    </div>
    <!-- /.box -->

    @include('shared._modal', [
       'model' => $poleWeight,
       'modelName' => 'Bảng khối lượng',
       'modelInformation' => $poleWeight->product->name,
       'routeName' => 'pole-weight'
   ])
@endsection

@section('javascript')
    <script src="{{ asset('plugins/input-mask/jquery.inputmask.numeric.extensions.js') }}"></script>
    <script src="//cdn.rawgit.com/rainabba/jquery-table2excel/1.1.0/dist/jquery.table2excel.min.js"></script>
    <script>
        $(function () {
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
                        targets: [ 3 ],
                        render: $.fn.dataTable.render.number('.', ','),
                        className   : 'dt-body-right'
                    },
                    {
                        targets: [ 4 ],
                        className   : 'dt-body-right'
                    },
                    {
                        targets: '_all',
                        className   : 'dt-head-center'
                    }
                ]
            });

            $('button.cancel').on('click', function (e) {
                e.preventDefault();
            });

            //create manufacturer order
            // $('#manufacturer-order').on('click', function () {
            //
            // });
            $('#export').on('click', function () {
                $('#contract-show').table2excel();
            });

        });
    </script>
@stop