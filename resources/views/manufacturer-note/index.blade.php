@extends('layouts.dashboard')

@section('content')
    <div class="box">
        <div class="box-header">
            <h3 class="box-title">Tổng hợp LXH</h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
            <table id="example2" class="table table-bordered table-striped compact hover row-border" style="width:100%">
                <thead>
                <tr>
                    <th>ĐVXH</th>
                    <th>Ngày xuất</th>
                    <th>Số LXH</th>
                    <th>Số đơn hàng</th>
                    <th>Tên sản phẩm</th>
                    <th>Số lượng xuất</th>
                    <th>LSX</th>
                    <th>Action</th>
                </tr>

                </thead>
                <tbody>
                @foreach ($outputOrderDetails as $outputOrderDetail)
                    <tr>
                        <td>{{ $outputOrderDetail->outputOrder->customer->short_name }}</td>
                        <td>{{ $outputOrderDetail->outputOrder->date }}</td>
                        <td>{{ $outputOrderDetail->outputOrder->number }}</td>
                        <td>{{ $outputOrderDetail->contractDetail->contract->number }}</td>
                        <td>{{ $outputOrderDetail->contractDetail->price->product->name }}</td>
                        <td>{{ $outputOrderDetail->quantity }}</td>
                        <td>{{ $outputOrderDetail->manufacturer_order_number }}</td>
                        <td>
                            <div class="btn-group">
                                <a href="{{ route('output-order.show', [$outputOrderDetail->output_order_id])}}" class="btn btn-success btn-xs">
                                    <i class="fa fa-pencil-square-o" aria-hidden="true"></i> Xem
                                </a>
                                <a href="{{ route('output-order.edit', [$outputOrderDetail->output_order_id])}}" class="btn btn-info btn-xs">
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
                        <th>Ngày xuất</th>
                        <th>Số LXH</th>
                        <th>Số đơn hàng</th>
                        <th>Tên sản phẩm</th>
                        <th>Số lượng xuất</th>
                        <th>LSX</th>
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
                var title = $(this).text();
                $(this).html( '<input type="text" style="width:100%;" placeholder="Tìm" />' );
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
                columnDefs: [
                    {
                        targets     : 1,
                        className   : 'dt-body-right'
                    },
                    {
                        targets     : [0,2,3,5,6],
                        className   : 'dt-body-center'
                    },
                    {
                        targets     : 4,
                        width       : '40%',
                    },
                    {
                        targets     : 7,
                        width       : '10%',
                    },
                    {
                        targets     : '_all',
                        className   : 'dt-head-center'
                    },
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