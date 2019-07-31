@extends('layouts.dashboard')

@section('content')
    <div class="box">
        <div class="box-header">
            <h3 class="box-title">Danh sách khách hàng</h3>
            <a href="{{ route('customers.create') }}" class="btn btn-primary pull-right">Thêm khách hàng mới</a>
        </div>
        <!-- /.box-header -->
        <div class="box-body table-responsive">
            <table id="example2" class="table table-bordered table-striped compact hover row-border" style="width:100%">
                <thead>
                <tr>
                    <th>STT</th>
                    <th>Mã khách</th>
                    <th>Tên khách hàng</th>
                    <th>Tên rút gọn</th>
                    <th>Địa chỉ</th>
                    <th>Mã số thuế</th>
                    <th>Xem</th>
                    <th>Sửa</th>
                </tr>

                </thead>
                <tbody>
                @foreach ($customers as $customer)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $customer->code }}</td>
                        <td>{{ $customer->name }}</td>
                        <td>{{ $customer->short_name }}</td>
                        <td>{{ $customer->address }}</td>
                        <td>{{ $customer->tax_registration_number }}</td>
                        <td>
                            <a href="{{ route('customers.show', $customer)}}" class="btn btn-success">
                                <i class="fa fa-pencil-square-o" aria-hidden="true"></i> Xem
                            </a>
                        </td>
                        <td>
                            <a href="{{ route('customers.edit', $customer)}}" class="btn btn-info">
                                <i class="fa fa-pencil-square-o" aria-hidden="true"></i> Sửa
                            </a>
                        </td>
                    </tr>
                @endforeach

                </tbody>
                <tfoot>
                    <tr>-
                        <th>STT</th>
                        <th>Mã khách</th>
                        <th>Tên khách hàng</th>
                        <th>Tên rút gọn</th>
                        <th>Địa chỉ</th>
                        <th>Mã số thuế</th>
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
                        targets : 0,
                    },
                    {
                        targets : 4,
                        width   : '10%'
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