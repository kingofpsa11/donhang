@extends('layouts.dashboard')

@section('content')
    <div class="box">
        <div class="box-header">
            <h3 class="box-title">Tổng hợp phiếu xuất kho</h3>
            <a class="btn btn-primary pull-right" href="{{ route('good-receive.create') }}">Tạo phiếu xuất mới</a>
        </div>
        <!-- /.box-header -->
        <div class="box-body table-responsive">
            <table id="example2" class="table table-bordered table-striped compact hover row-border" style="width:100%">
                <thead>
                <tr>
                    <th>Ngày lập</th>
                    <th>Đơn vị nhận hàng</th>
                    <th>Số phiếu</th>
                    <th>Tên sản phẩm</th>
                    <th>Số lượng</th>
                    <th>Số lượng thực xuất</th>
                    <th>Trạng thái</th>
                    <th>Action</th>
                </tr>
                
                </thead>
                <tbody>
                @foreach ($goodDeliveryDetails as $goodDeliveryDetail)
                    <tr>
                        <td>{{ $goodDeliveryDetail->goodDelivery->date }}</td>
                        <td>{{ $goodDeliveryDetail->goodDelivery->customer->name }}</td>
                        <td>{{ $goodDeliveryDetail->goodDelivery->number }}</td>
                        <td>{{ $goodDeliveryDetail->product->name }}</td>
                        <td>{{ $goodDeliveryDetail->quantity }}</td>
                        <td>{{ $goodDeliveryDetail->actual_quantity }}</td>
                        <td>{{ $goodDeliveryDetail->status }}</td>
                        <td>
                            <a href="{{ route('good-delivery.show', $goodDeliveryDetail->good_delivery_id)}}" class="btn btn-success">
                                <i class="fa fa-pencil-square-o" aria-hidden="true"></i> Xem
                            </a>
                            <a href="{{ route('good-delivery.edit', $goodDeliveryDetail->good_delivery_id)}}" class="btn btn-info">
                                <i class="fa fa-pencil-square-o" aria-hidden="true"></i> Sửa
                            </a>
                        </td>
                    </tr>
                @endforeach
                
                </tbody>
                <tfoot>
                <tr>
                    <th>Ngày lập</th>
                    <th>Đơn vị nhận hàng</th>
                    <th>Số phiếu</th>
                    <th>Tên sản phẩm</th>
                    <th>Số lượng</th>
                    <th>Số lượng thực xuất</th>
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