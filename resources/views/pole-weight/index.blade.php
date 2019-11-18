@extends('layouts.dashboard')

@section('content')
    <div class="box">
        <div class="box-header">
            <h3 class="box-title">Tổng hợp định mức</h3>
            <a href="{{ route('pole-weight.create') }}" class="btn btn-primary pull-right">Tạo bảng tính khối lượng mới</a>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
            <table id="table" class="table table-bordered table-striped compact hover row-border" style="width:100%">
                <thead>
                <tr>
                    <th>Tên sản phẩm</th>
                    <th>Nhóm cột thép</th>
                    <th>Tỷ lệ nhân công</th>
                    <th>Diện tích</th>
                    <th>Khối lượng</th>
                    <th>Đơn giá</th>
                    <th>Giá bán</th>
                    <th>Xem</th>
                    <th>Sửa</th>
                </tr>

                </thead>
                <tbody>
                @foreach ($poleWeights as $poleWeight)
                    <tr>
                        <td>{{ $poleWeight->product->name }}</td>
                        <td>{{ $poleWeight->expenseOfPole->name }}</td>
                        <td>{{ $poleWeight->ty_le_nhan_cong }}</td>
                        <td>{{ $poleWeight->area }}</td>
                        <td>{{ $poleWeight->weight }}</td>
                        <td>{{ $poleWeight->unit_price }}</td>
                        <td>{{ $poleWeight->price }}</td>
                        <td>
                            <a href="{{ route('pole-weight.show', $poleWeight)}}" class="btn btn-success">
                                <i class="fa fa-pencil-square-o" aria-hidden="true"></i> Xem
                            </a>
                        </td>
                        <td>
                            <a href="{{ route('pole-weight.edit', $poleWeight)}}" class="btn btn-info">
                                <i class="fa fa-pencil-square-o" aria-hidden="true"></i> Sửa
                            </a>
                        </td>
                    </tr>
                @endforeach

                </tbody>
                <tfoot>
                    <tr>
                        <th>Tên sản phẩm</th>
                        <th>Nhóm cột thép</th>
                        <th>Tỷ lệ nhân công</th>
                        <th>Diện tích</th>
                        <th>Khối lượng</th>
                        <th>Đơn giá</th>
                        <th>Giá bán</th>
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
            $('#table tfoot th').each( function () {
                $(this).html('<input type="text" style="width:100%;" placeholder="Tìm" />');
            });

            let table = $('#table').DataTable({
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
                    { "data" : "product" },
                    { "data" : "expense" },
                    { "data" : "ty_le_nhan_cong" },
                    { "data" : "area" },
                    { "data" : "weight" },
                    { "data" : "unit_price" },
                    { "data" : "price" },
                    {
                        data    : "view",
                    },
                    {
                        data    : 'edit'
                    }
                ],
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