@extends('layouts.dashboard')

@section('title', 'Đơn hàng')

@section('content')
    
    <!-- Main content -->
    <section class="content container-fluid">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title text-center">LỆNH XUẤT HÀNG</h3>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="control-label">Đơn vị xuất hàng</label>
                            <input type="text" class="form-control" readonly value="{{ $outputOrder->customer->name }}">
                        </div>
                    </div>
                    <!-- /.col -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Người nhận hàng</label>
                            <input type="text" class="form-control" value="{{ $outputOrder->customer_user }}" readonly>
                            <!-- /.input group -->
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Số lệnh xuất hàng</label>
                            <input type="text" class="form-control" readonly value="{{ $outputOrder->number }}">
                        </div>
                    </div>
                    <!-- /.col -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Ngày xuất hàng</label>
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input type="text" class="form-control" data-inputmask="'alias': 'dd/mm/yyyy'" data-mask name="contract[date]" value="{{ $outputOrder->date }}" readonly>
                            </div>
                            <!-- /.input group -->
                        </div>
                    </div>

                </div>
                <!-- /.row -->
            </div>
            <!-- /.box-header -->
            <div class="box-body table-responsive no-padding">
                <table class="table table-bordered table-striped hover" id="contract-show">
                    <thead>
                    <tr>
                        <th>STT</th>
                        <th>Số đơn hàng</th>
                        <th class="">Số LSX</th>
                        <th>Mã sản phẩm</th>
                        <th class="col-md-6">Tên sản phẩm</th>
                        <th class="col-md-1">Số lượng</th>
                        <th>Ghi chú</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($outputOrder->outputOrderDetails as $outputOrderDetail)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $outputOrderDetail->contractDetail->contract_id }}</td>
                            <td>{{ $outputOrderDetail->contractDetail->manufacturer_order_number }}</td>
                            <td>{{ $outputOrderDetail->contractDetail->price->product->code }}</td>
                            <td>{{ $outputOrderDetail->contractDetail->price->product->name }}</td>
                            <td>{{ $outputOrderDetail->quantity }}</td>
                            <td>{{ $outputOrderDetail->note }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <!-- /.box-body -->
            <div class="box-footer">
                <div class="row hidden">
                    <div class="col-xs-6 sign-name" style="text-align: center">
                        <p>PHÒNG KẾ HOẠCH KINH DOANH</p>
                    </div>
                    <div class="col-xs-6 sign-name" style="text-align: center">
                        <p>NGƯỜI LẬP</p>
                    </div>
                </div>
                <div class="control-button text-right">
                    <div>
                        <a href="{{ route('output-orders.edit', $outputOrder) }}" class="btn btn-primary">
                            <i class="fa fa-pencil-square-o" aria-hidden="true"></i> Sửa
                        </a>
                        <button class="btn btn-default print">In</button>
                        <button class="btn btn-danger" data-toggle="modal" data-target="#modal">Xoá</button>

                        <form action="{{ route('output-orders.update', $outputOrder) }}" method="POST" style="display: inline-block;">
                            @csrf
                            @method('PATCH')
                            <input type="submit" value="Duyệt" class="btn btn-success" name="approved">
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.box -->
    </section>
    @include('shared._modal', [
        'model' => $outputOrder,
        'modelName' => 'Lệnh xuất hàng',
        'modelInformation' => $outputOrder->number,
        'routeName' => 'output-orders'
    ])
@endsection

@section('javascript')
    <script>
        $(document).ready(function () {
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
            });

            $(window).on('beforeprint', function () {

            });

            $('.print').on('click', function (e) {
                e.preventDefault();
                window.print();
            })
        });
    </script>
@stop