@extends('layouts.dashboard')

@section('content')
    <div class="box">
        <div class="box-header">
            <h3 class="box-title">Tổng hợp đơn hàng</h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
            <table id="example2" class="table table-bordered table-striped compact hover row-border" style="width:100%">
                <thead>
                <tr>
                    <th>ĐVXH</th>
                    <th>Số LXH</th>
                    <th>Số đơn hàng</th>
                    <th>Tên sản phẩm</th>
                    <th>Số lượng xuất</th>
                    <th>Ngày xuất</th>
                    <th>LSX</th>
                    <th>Trạng thái</th>
                    <th>Action</th>
                </tr>

                </thead>
                <tbody>
                @foreach ($output_order_details as $output_order_detail)
                    <tr>
                        <td>{{ $output_order_detail->outputOrder->customer->short_name }}</td>
                        <td>{{ $output_order_detail->outputOrder->number }}</td>
                        <td>{{ $output_order_detail->contractDetails->number }}</td>
                        <td>{{ $output_order_detail->contractDetails->price->product->name }}</td>
                        <td>{{ $output_order_detail->quantity }}</td>
                        <td>{{ $output_order_detail->outputOrder->date }}</td>
                        <td>{{ $output_order_detail->manufacturer_order_number }}</td>
                        <td>{{ $output_order_detail->status }}</td>
                        <td>
                            <div class="btn-group">
                                <a href="{{ route('output-order.show', ['output_order' => $output_order_detail->output_order_id])}}" class="btn btn-success btn-xs">
                                    <i class="fa fa-pencil-square-o" aria-hidden="true"></i> Xem
                                </a>
                                <a href="{{ route('output-order.edit', ['output_order' => $output_order_detail->output_order_id])}}" class="btn btn-info btn-xs">
                                    <i class="fa fa-pencil-square-o" aria-hidden="true"></i> Sửa
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforeach

                </tbody>
                <tfoot>
                    <tr>
                        <th>ĐVXH</th>
                        <th>Số LXH</th>
                        <th>Số đơn hàng</th>
                        <th>Tên sản phẩm</th>
                        <th>Số lượng xuất</th>
                        <th>Ngày xuất</th>
                        <th>LSX</th>
                        <th>Trạng thái</th>
                        <th>Action</th>
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
                var title = $(this).text();
                $(this).html( '<input type="text" style="width:100%;" placeholder="Search '+title+'" />' );
            } );


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
                        "data" : "date",
                        className   : 'dt-body-right'
                    },
                    {
                        "data"      : "deadline",
                        className   : 'dt-body-right'
                    },
                    { "data" : "order" },
                    {
                        "data"      : "status",
                        "render"    : function (data) {
                            if (data == 10) {
                                return '<span class="label label-warning">Đang sản xuất</span>';
                            } else if (data == 0) {
                                return '<span class="label label-success">Xong</span>';
                            }
                        }
                    },
                    {
                        "data" : "action",
                        "className" : 'dt-body-right',
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
                    console.log(that.search());
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