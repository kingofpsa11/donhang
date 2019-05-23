@extends('layouts.dashboard')

@section('content')
    <div class="box">
        <div class="box-header">
            <h3 class="box-title">Tổng hợp phiếu chuyển kho</h3>
            <a href="{{ route('good-transfer.create') }}" class="btn btn-primary pull-right">Tạo đơn hàng</a>
        </div>
        <!-- /.box-header -->
        <div class="box-body table-responsive">
            <table id="example2" class="table table-bordered table-striped compact hover row-border" style="width:100%">
                <thead>
                <tr>
                    <th>Số phiếu nhập</th>
                    <th>Số phiếu xuất</th>
                    <th>Ngày lập</th>
                    <th>Đơn vị xuất</th>
                    <th>Đơn vị nhập</th>
                    <th>Trạng thái</th>
                    <th>Người lập</th>
                    <th>Action</th>
                </tr>

                </thead>
                <tbody>
                @foreach ($goodTransfers as $goodTransfer)
                    <tr>
                        <td>{{ $goodTransfer->receive_number ?? '' }}</td>
                        <td>{{ $goodTransfer->delivery_number ?? ''}}</td>
                        <td>{{ $goodTransfer->date }}</td>
                        <td>{{ $goodTransfer->delivery_store }}</td>
                        <td>{{ $goodTransfer->receive_store }}</td>
                        <td>{{ $goodTransfer->status }}</td>
                        <td>{{ $goodTransfer->user->name }}</td>
                        <td>
                            <div class="btn-group">
                                <a href="{{ route('good-transfer.show', $goodTransfer)}}" class="btn btn-success btn-xs">
                                    <i class="fa fa-pencil-square-o" aria-hidden="true"></i> Xem
                                </a>
                                @role('User')
                                <a href="{{ route('good-transfer.edit', $goodTransfer)}}" class="btn btn-info btn-xs">
                                    <i class="fa fa-pencil-square-o" aria-hidden="true"></i> Sửa
                                </a>
                                @endrole
                            </div>
                        </td>
                    </tr>
                @endforeach

                </tbody>
                <tfoot>
                    <tr>
                        <th>Số phiếu nhập</th>
                        <th>Số phiếu xuất</th>
                        <th>Ngày lập</th>
                        <th>Đơn vị xuất</th>
                        <th>Đơn vị nhập</th>
                        <th>Trạng thái</th>
                        <th>Người lập</th>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div class="box-footer">

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
                        targets : 5,
                        width   : '10%',
                        "render"    : function (data) {
                            if (data === '10') {
                                return '<span class="label label-warning">Đang chờ duyệt</span>';
                            } else if (data === '5') {
                                return '<span class="label label-success">Đã duyệt</span>';
                            }
                        }
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