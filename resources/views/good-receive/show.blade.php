@extends('layouts.dashboard')

@section('title', 'Phiếu nhập kho')

@section('content')

    <!-- Main content -->
    <section class="content container-fluid">
        <div class="box">
            <div class="box-header">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Đơn vị giao hàng</label>
                            <input type="text" class="form-control" value="{{ $goodReceive->supplier->name }}" readonly="">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Người giao</label>
                            <input type="text" class="form-control" name="goodReceive[supplier]" value="{{ $goodReceive->supplier_user }}" readonly>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Ngày</label>
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input type="text" class="form-control" value="{{ $goodReceive->date }}" name="goodReceive[date]" readonly>
                            </div>
                            <!-- /.input group -->
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Số phiếu</label>
                            <input type="text" class="form-control" name="goodReceive[number]" value="{{ $goodReceive->number }}" readonly>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body table-responsive">
                <table class="table table-bordered table-striped hover" id="contract-show">
                    <thead>
                    <tr>
                        <th>STT</th>
                        <th class="col-md-2">Mã sản phẩm</th>
                        <th class="col-md-6">Tên sản phẩm</th>
                        <th class="col-md-1">Đvt</th>
                        @role(4)
                        <th class="col-md-2">Định mức</th>
                        @endrole
                        <th class="col-md-2">Kho</th>
                        <th class="col-md-1">Số lượng</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($goodReceive->goodReceiveDetails as $goodReceiveDetail)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $goodReceiveDetail->product->code }}</td>
                            <td>{{ $goodReceiveDetail->product->name ?? ''}}</td>
                            <td></td>
                            @role(4)
                            <td>{{ $goodReceiveDetail->bom->name ?? '' }}</td>
                            @endrole
                            <td>{{ $goodReceiveDetail->store->code }}</td>
                            <td>{{ $goodReceiveDetail->quantity }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <!-- /.box-body -->
            <div class="box-footer">
                <div class="row">
                    <div class="col-md-12 text-right">
                        <a href="{{ route('good-receive.create') }}" class="btn btn-success">Tạo mới</a>
                        <button class="btn btn-primary" id="export">Xuất Excel</button>
                        <a href="{{ route('good-receive.edit', $goodReceive)}}" class="btn btn-info">
                            <i class="fa fa-pencil-square-o" aria-hidden="true"></i> Sửa
                        </a>
                        <button class="btn btn-danger" id="delete" data-toggle="modal" data-target="#modal">Xóa</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.box -->
    </section>

    @include('shared._modal', [
        'model' => $goodReceive,
        'modelName' => 'phiếu nhập kho',
        'modelInformation' => $goodReceive->number,
        'routeName' => 'good-receive'
    ])
@endsection

@section('javascript')
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
                        targets: '_all',
                        className   : 'dt-head-center'
                    }
                ]
            });

            $('button.cancel').on('click', function (e) {
                e.preventDefault();
            });

            $('#export').on('click', function () {
                $('#contract-show').table2excel();
            });

        });
    </script>
@stop