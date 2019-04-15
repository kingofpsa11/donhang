@extends('layouts.dashboard')

@section('title', 'Đơn hàng')

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
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">Nội dung đơn hàng</h3>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Đơn vị đặt hàng</label>
                            <input type="text" class="form-control" disabled value="{{ $contract->customer_id }}">
                        </div>
                    </div>
                    <!-- /.col -->
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Số đơn hàng</label>
                            <input type="text" class="form-control" disabled value="{{ $contract->number }}">
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
                                <input type="text" class="form-control" data-inputmask="'alias': 'dd/mm/yyyy'" data-mask name="contract[date]" value="<?php echo date_format(date_create($contract->date), "d/m/Y"); ?>" disabled>
                            </div>
                            <!-- /.input group -->
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Giá trị đơn hàng</label>
                            <input type="text" class="form-control" disabled value="<?php echo number_format($contract->total_value, 0, ',', '.') ?>">
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
                            <th class="col-md-5">Tên sản phẩm</th>
                            <th class="col-md-1">Số lượng</th>
                            <th class="col-md-1">Đơn giá</th>
                            <th class="col-md-2">Tiến độ</th>
                            <th class="col-md-2">Ghi chú</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($contract->contract_details as $contract_detail)
                            <tr>
                                <td>{{ $contract_detail->price->product->name }}</td>
                                <td>{{ $contract_detail->quantity }}</td>
                                <td>{{ $contract_detail->selling_price }}</td>
                                <td>{{ $contract_detail->deadline }}</td>
                                <td>{{ $contract_detail->note }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <!-- /.box-body -->
        </div>
        <!-- /.box -->
    </section>
@endsection

@section('javascript')
    <script>
        // $('[data-mask]').inputmask();

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
                    render: function (data) {
                        return moment(data).format('DD/MM/YYYY')
                    },
                    className   : 'dt-body-right'
                }
            ]
        });
        $('button.cancel').on('click', function (e) {
            e.preventDefault();
        })
    </script>
@stop