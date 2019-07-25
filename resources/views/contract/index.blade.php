@extends('layouts.dashboard')

@section('content')
    <div class="box">
        <div class="box-header">
            <h3 class="box-title">Tổng hợp đơn hàng</h3>
            <a href="{{ route('contracts.create') }}" class="btn btn-primary pull-right">Tạo đơn hàng</a>
        </div>
        <!-- /.box-header -->
        <div class="box-body table-responsive">
            <table id="example2" class="table table-bordered table-striped compact hover row-border" style="width:100%">
                <thead>
                <tr>
                    <th>ĐVĐH</th>
                    <th>Số đơn hàng</th>
                    <th>Tên sản phẩm</th>
                    <th>Số lượng</th>
                    <th>Đơn giá</th>
                    <th>Ngày lập</th>
                    <th>Tiến độ</th>
                    <th>LSX</th>
                    <th>Trạng thái</th>
                    <th>Action</th>
                </tr>

                </thead>
                <tbody>
                @foreach ($contractDetails as $contractDetail)
                    <tr>
                        <td>{{ $contractDetail->contract->customer->short_name }}</td>
                        <td>{{ $contractDetail->contract->number }}</td>
                        <td>{{ $contractDetail->price->product->name }}</td>
                        <td>{{ $contractDetail->quantity }}</td>
                        <td>{{ $contractDetail->selling_price }}</td>
                        <td>{{ $contractDetail->contract->date }}</td>
                        <td>{{ $contractDetail->deadline }}</td>
                        <td>{{ $contractDetail->manufacturerOrderDetail->manufacturerOrder->number ?? '' }}</td>
                        <td>{{ $contractDetail->status }}</td>
                        <td>
                            <a href="{{ route('contracts.show', $contractDetail->contract)}}" class="btn btn-success">
                                <i class="fa fa-tag" aria-hidden="true"></i> Xem
                            </a>
                            <a href="{{ route('contracts.edit', $contractDetail->contract)}}" class="btn btn-info">
                                <i class="fa fa-pencil-square-o" aria-hidden="true"></i> Sửa
                            </a>
                        </td>
                    </tr>
                @endforeach

                </tbody>
                <tfoot>
                    <tr>
                        <th>ĐVĐH</th>
                        <th>Số đơn hàng</th>
                        <th>Tên sản phẩm</th>
                        <th>Số lượng</th>
                        <th>Đơn giá</th>
                        <th>Ngày lập</th>
                        <th>Tiến độ</th>
                        <th>LSX</th>
                        <th>Trạng thái</th>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>
        <!-- /.box-body -->
    </div>
    <!-- /.box -->
@stop

@section('javascript')
    <script>
        $(document).ready(function () {
            $('#example2 tfoot th').each( function () {
                $(this).html('<input type="text" style="width:100%;" placeholder="Tìm" />');
            });

            let table = $('#example2').DataTable({
                'paging': true,
                'ordering': true,
                'info': true,
                'autoWidth' : true,
                'searching': true,
                "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
                "language": {
                    "info": "Từ _START_ đến _END_ trong _TOTAL_ dòng",
                    "lengthMenu" : "Hiện _MENU_ dòng"
                },
                "columns" : [
                    { "data" : "customer" },
                    { "data" : "number" },
                    { "data" : "product" },
                    { "data" : "quantity" },
                    {
                        "data"      : "selling_price",
                        render      : $.fn.dataTable.render.number( '.', ','),
                        className   : 'dt-body-right'
                    },
                    {
                        "data"      : "date",
                        className   : 'dt-body-right'
                    },
                    {
                        "data"      : "deadline",
                        className   : 'dt-body-right'
                    },
                    {
                        "data"      : "order",
                        className   : 'dt-body-center'
                    },
                    {
                        "data"      : "status",
                        "render"    : function (data) {
                            if (data === '10') {
                                return '<span class="label label-warning">Đang sản xuất</span>';
                            } else if (data === '0') {
                                return '<span class="label label-success">Xong</span>';
                            }
                        },
                        className   : 'dt-body-center'
                    },
                    {
                        "data" : "action",
                        "className" : 'dt-body-center',
                    }
                ],
                columnDefs: [
                    {
                        targets: "_all",
                        className   : 'dt-head-center',
                    },
                    {
                        targets : [0,1,3],
                        width   : '5%'
                    },
                    {
                        targets : 2,
                        width   : '30%'
                    },
                    {
                        targets : [4,5,6],
                        width   : '10%'
                    },
                    {
                        targets : 9,
                        width   : '10%'
                    }
                ]
            });

            table.columns().every( function () {
                var that = this;

                $( 'input', this.footer() ).on( 'keyup change', function () {
                    if ( that.search() !== this.value ) {
                        that
                            .search( this.value )
                            .draw();
                    }
                } );
            } );
        });
    </script>
@stop