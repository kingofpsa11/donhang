@extends('layouts.dashboard')

@section('content')
    <div class="box">
        <div class="box-header">
            <h3 class="box-title">Tổng hợp định mức</h3>
            <a href="{{ route('boms.create') }}" class="btn btn-primary pull-right">Tạo định mức mới</a>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
            <table id="table" class="table table-bordered table-striped compact hover row-border" style="width:100%">
                <thead>
                <tr>
                    <th>Mã sản phẩm</th>
                    <th>Tên sản phẩm</th>
                    <th>Trạng thái</th>
                    <th>Xem</th>
                    <th>Sửa</th>
                </tr>

                </thead>
                <tbody>
                @foreach ($boms as $bom)
                    <tr>
                        <td>{{ $bom->product->code }}</td>
                        <td>{{ $bom->product->name }}</td>
                        <td>{{ $bom->status }}</td>
                        <td>
                            <a href="{{ route('boms.show', [$bom])}}" class="btn btn-success">
                                <i class="fa fa-pencil-square-o" aria-hidden="true"></i> Xem
                            </a>
                        </td>
                        <td>
                            <a href="{{ route('boms.edit', [$bom])}}" class="btn btn-info">
                                <i class="fa fa-pencil-square-o" aria-hidden="true"></i> Sửa
                            </a>
                        </td>
                    </tr>
                @endforeach

                </tbody>
                <tfoot>
                    <tr>
                        <th>Mã sản phẩm</th>
                        <th>Tên sản phẩm</th>
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
                    { "data" : "code" },
                    { "data" : "name" },
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