@extends('layouts.dashboard')

@section('content')
    <div class="box">
        <div class="box-header">
            <h3 class="box-title">Tổng hợp tồn kho</h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body table-responsive">
            <table id="example2" class="table table-bordered table-striped compact hover row-border" style="width:100%">
                <thead>
                <tr>
                    <th>STT</th>
                    <th >Mã sản phẩm</th>
                    <th>Tên sản phẩm</th>
                    <th>Kho</th>
                    <th>Số lượng nhập</th>
                    <th>Số lượng xuất</th>
                    <th>Tồn cuối</th>
                </tr>

                </thead>
                <tbody>
                @foreach ($inventories as $inventory)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $inventory->code }}</td>
                        <td>{{ $inventory->product_name }}</td>
                        <td>{{ $inventory->store_name }}</td>
                        <td>{{ $inventory->receive ?? ''}}</td>
                        <td>{{ $inventory->delivery }}</td>
                        <td>{{ $inventory->total ?? ''}}</td>
                    </tr>
                @endforeach

                </tbody>
                <tfoot>
                    <tr>
                        <th>STT</th>
                        <th >Mã sản phẩm</th>
                        <th>Tên sản phẩm</th>
                        <th>Kho</th>
                        <th>Số lượng nhập</th>
                        <th>Số lượng xuất</th>
                        <th>Tồn cuối</th>
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