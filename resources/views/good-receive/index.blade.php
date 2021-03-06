@extends('layouts.dashboard')

@section('content')
    <div class="box">
        <div class="box-header">
            <h3 class="box-title">Tổng hợp phiếu nhập kho</h3>
            <a class="btn btn-primary pull-right" href="{{ route('good-receive.create') }}">Tạo phiếu nhập mới</a>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
            <table id="example2" class="table table-bordered table-striped compact hover row-border" style="width:100%">
                <thead>
                <tr>
                    <th>Ngày lập</th>
                    <th>Đơn vị giao hàng</th>
                    <th>Số phiếu</th>
                    <th>Tên sản phẩm</th>
                    <th>Số lượng</th>
                    <th>Trạng thái</th>
                    <th>Xem</th>
                    <th>Sửa</th>
                </tr>

                </thead>
                <tbody>
                @foreach ($goodReceiveDetails as $goodReceiveDetail)
                    <tr>
                        <td>{{ $goodReceiveDetail->goodReceive->date }}</td>
                        <td>{{ $goodReceiveDetail->goodReceive->supplier->name }}</td>
                        <td>{{ $goodReceiveDetail->goodReceive->number }}</td>
                        <td>{{ $goodReceiveDetail->product->name }}</td>
                        <td>{{ $goodReceiveDetail->quantity }}</td>
                        <td>{{ $goodReceiveDetail->status }}</td>
                        <td>
                            <a href="{{ route('good-receive.show', $goodReceiveDetail->good_receive_id)}}" class="btn btn-success">
                                <i class="fa fa-pencil-square-o" aria-hidden="true"></i> Xem
                            </a>
                        </td>
                        <td>
                            <a href="{{ route('good-receive.edit', $goodReceiveDetail->good_receive_id)}}" class="btn btn-info">
                                <i class="fa fa-pencil-square-o" aria-hidden="true"></i> Sửa
                            </a>
                        </td>
                    </tr>
                @endforeach

                </tbody>
                <tfoot>
                    <tr>
                        <th>Ngày lập</th>
                        <th>Đơn vị giao hàng</th>
                        <th>Số phiếu</th>
                        <th>Tên sản phẩm</th>
                        <th>Số lượng</th>
                        <th>Trạng thái</th>
                        <td>Xem</td>
                        <td>Sửa</td>
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