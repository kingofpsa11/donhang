@extends('layouts.dashboard')

@section('content')
    <div class="box">
        <div class="box-header">
            <h3 class="box-title">Tổng hợp đơn hàng</h3>
            <a href="{{ route('contracts.create') }}" class="btn btn-primary pull-right">Tạo đơn hàng</a>
        </div>
        <!-- /.box-header -->
        <div class="box-body table-responsive">
            <table id="example2" class="table table-bordered table-striped compact hover ">
                <thead>
                    <tr>
                        <th>ĐVĐH</th>
                        <th>Số đơn hàng</th>
                        <th>Tên sản phẩm</th>
                        <th>Số lượng</th>
                        <th>Đơn giá</th>
                        <th>Ngày lập</th>
                        <th>Tiến độ</th>
                        <th>LSX</th>
                        <th>Trạng thái</th>
                        <th>Xem</th>
                        <th>Sửa</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>ĐVĐH</th>
                        <th>Số đơn hàng</th>
                        <th>Tên sản phẩm</th>
                        <th>Số lượng</th>
                        <th>Đơn giá</th>
                        <th>Ngày lập</th>
                        <th>Tiến độ</th>
                        <th>LSX</th>
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
                'lengthChange'  : false,
                'processing'    : true,
                'serverSide'    : true,
                'ajax'          : {
                    'url'           : '{{ route('contracts.all_contracts') }}',
                    'dataType'      : 'json',
                    'type'          : 'POST',
                    'data'          : { _token: "{{ csrf_token() }}" }
                },
                'columns'       : [
                    {
                        data : "customer",
                    },
                    {
                        data : "number",
                        className: 'dt-body-center'
                    },
                    { data : "product" },
                    { data : "quantity" },
                    {
                        data        : "selling_price",
                        render      : $.fn.dataTable.render.number('.', ','),
                        className   : 'dt-body-right'
                    },
                    { data : "date" },
                    { data : "deadline" },
                    { data : "order" },
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
                        targets     : [5,6],
                        render      : function(data) {
                            return moment(data).format("DD/MM/YYYY");
                        },
                        className   : 'dt-body-right'
                    },
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