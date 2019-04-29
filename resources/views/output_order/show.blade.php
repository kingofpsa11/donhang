@extends('layouts.dashboard')

@section('title', 'Đơn hàng')


@section('content')
    
    <!-- Main content -->
    <section class="content container-fluid">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">Nội dung</h3>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Đơn vị xuất hàng</label>
                            <input type="text" class="form-control" readonly value="{{ $outputOrder->customer_id }}">
                        </div>
                    </div>
                    <!-- /.col -->
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Số đơn hàng</label>
                            <input type="text" class="form-control" readonly value="{{ $outputOrder->number }}">
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
                        <th>Số LSX</th>
                        <th>Mã sản phẩm</th>
                        <th>Tên sản phẩm</th>
                        <th>Số lượng</th>
                        <th>Ghi chú</th>
                    </tr>
                    </thead>
                    <tbody>
                    @php( $i = 0)
                    @foreach ($outputOrder->outputOrderDetails as $outputOrderDetail)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>{{ $outputOrderDetail->contractDetail->contract_id }}</td>
                            <td>{{ $outputOrderDetail->contractDetail->manufacturer_order_number }}</td>
                            <td>{{ $outputOrderDetail->contractDetail->price->product->code }}</td>
                            <td>{{ $outputOrderDetail->contractDetail->price->product->name }}</td>
                            <td>{{ $outputOrderDetail->quantity }}</td>
                            <td>{{ $outputOrderDetail->note }}</td>
                        </tr>
                        @php($i++)
                    @endforeach
                    </tbody>
                </table>
            </div>
            <!-- /.box-body -->
            <div class="box-footer">
                <div class="row">
                    <div class="col-md-1 pull-right">
                        <a href="{{ route('output-order.edit', [$outputOrder->id]) }}" class="btn btn-info">
                            <i class="fa fa-pencil-square-o" aria-hidden="true"></i> Sửa
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.box -->
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