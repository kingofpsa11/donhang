@extends('layouts.dashboard')

@section('content')
    <div class="box">
        <div class="box-header">
            <h3 class="box-title">Tổng hợp lệnh sản xuất</h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
            <table id="example2" class="table table-bordered table-striped compact hover row-border" style="width:100%">
                <thead>
                <tr>
                    <th>Ngày lập</th>
                    <th>LSX</th>
                    <th>Mã sản phẩm</th>
                    <th>Tên sản phẩm</th>
                    <th>Số lượng</th>
                    <th>Tiến độ</th>
                    <th>Trạng thái</th>
                    <th>Action</th>
                </tr>

                </thead>
                <tbody>
                @foreach ($manufacturerOrderDetails as $manufacturerOrderDetail)
                    <tr>
                        <td>{{ $manufacturerOrderDetail->manufacturerOrder->contract->date }}</td>
                        <td>{{ $manufacturerOrderDetail->manufacturerOrder->number }}</td>
                        <td>{{ $manufacturerOrderDetail->contractDetail->price->product->code }}</td>
                        <td>{{ $manufacturerOrderDetail->contractDetail->price->product->name }}</td>
                        <td>{{ $manufacturerOrderDetail->contractDetail->quantity }}</td>
                        <td>{{ $manufacturerOrderDetail->contractDetail->deadline }}</td>
                        <td>{{ $manufacturerOrderDetail->contractDetail->status }}</td>
                        <td>
                            <div class="btn-group">
                                <a href="{{ route('manufacturer-order.show', $manufacturerOrderDetail->manufacturerOrder)}}" class="btn btn-info">
                                    <i class="fa fa-pencil-square-o" aria-hidden="true"></i> Xem
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforeach

                </tbody>
                <tfoot>
                    <tr>
                        <th>Ngày lập</th>
                        <th>LSX</th>
                        <th>Mã sản phẩm</th>
                        <th>Tên sản phẩm</th>
                        <th>Số lượng</th>
                        <th>Tiến độ</th>
                        <th>Trạng thái</th>
                        <td>Action</td>
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
                columnDefs: [
                    {
                        targets     : [0,5],
                        className   : 'dt-body-right',
                        width       : '8%'
                    },
                    {
                        targets     : 1,
                        width       : '5%',
                        className   : 'dt-body-center'
                    },
                    {
                        targets     : 2,
                        className   : 'dt-body-center',
                        width       : '10%',
                    },
                    {
                        targets     : 3,
                        width       : '40%',
                    },
                    {
                        targets     : 4,
                        className   : 'dt-body-center',
                        width       : '5%',
                    },
                    {
                        targets     : 6,
                        className   : 'dt-body-center',
                        width       : '10%',
                    },
                    {
                        targets     : 7,
                        className   : 'dt-body-center',
                        width       : '5%',
                    },
                    {
                        targets: "_all",
                        className   : 'dt-head-center',
                    },
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