@extends('layouts.dashboard')

@section('title', 'Sản phẩm')

@section('content')
    <div class="box">
        <!-- /.box-header -->
        <div class="box-body table-responsive">
            <table id="example2" class="table table-bordered table-striped compact hover row-border" style="width:100%">
                <thead>
                    <tr>
                        <th>Nhóm</th>
                        <th>Mã sản phẩm</th>
                        <th>Tên sản phẩm</th>
                        <th>Trạng thái</th>
                        <th>Xem</th>
                        <th>Sửa</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>Nhóm</th>
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
            $('#example2 tfoot th').each( function () {
                $(this).html('<input type="text" style="width:100%;" placeholder="Tìm" />');
            });

            let table = $('#example2').DataTable({
                "language": {
                    "info": "Từ _START_ đến _END_ trong _TOTAL_ dòng",
                    "lengthMenu" : "Hiện _MENU_ dòng"
                },
                searchDelay     : 300,
                'processing'    : true,
                'serverSide'    : true,
                'ajax'          : {
                    'url'           : '{{ route('products.all_products') }}',
                    'dataType'      : 'json',
                    'type'          : 'GET',
                    'data'          : { _token: "{{ csrf_token() }}" }
                },
                'columns'       : [
                    {data : "category" },
                    {
                        data : "code",
                    },
                    { data : "name" },
                    {
                        data : "status",
                        render    : function (data) {
                            switch (data) {
                                case 10:
                                    return '<span class="label label-primary">Đang trình ký</span>';
                                case 5:
                                    return '<span class="label label-warning">Đang sản xuất</span>';
                                case 0:
                                    return '<span class="label label-success">Xong</span>';
                                default:
                                    return `<span class="label label-default">${data}</span>`;
                            }
                        },
                    },
                    {
                        data    : "view",
                    },
                    {
                        data    : "edit",
                    },
                ],
                columnDefs: [
                    {
                        targets     : '_all',
                        className   : 'dt-head-center'
                    }
                ]
            });

            table.columns().every( function () {
                let that = this;

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
