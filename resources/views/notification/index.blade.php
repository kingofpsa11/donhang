@extends('layouts.dashboard')

@section('content')
    <div class="box">
        <div class="box-header">
            <h3 class="box-title">Tổng hợp đơn hàng</h3>
            <a href="{{ route('contracts.create') }}" class="btn btn-primary pull-right">Tạo đơn hàng</a>
        </div>
        <!-- /.box-header -->
        <div class="box-body table-responsive">
            <div class="table-responsive mailbox-messages">
                <table class="table table-hover table-striped">
                    <tbody>
                    <tr>
                        <td><input type="checkbox"></td>
                        <td class="mailbox-star"><a href="#"><i class="fa fa-star text-yellow"></i></a></td>
                        <td class="mailbox-name"><a href="read-mail.html">Alexander Pierce</a></td>
                        <td class="mailbox-subject"><b>AdminLTE 2.0 Issue</b> - Trying to find a solution to this problem...
                        </td>
                        <td class="mailbox-attachment"></td>
                        <td class="mailbox-date">5 mins ago</td>
                    </tr>
                    </tbody>
                </table>
                <!-- /.table -->
            </div>
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
                    'url'           : '{{ route('contracts.all_contracts') }}',
                    'dataType'      : 'json',
                    'type'          : 'POST',
                    'data'          : { _token: "{{ csrf_token() }}" }
                },
                'columns'       : [
                    {data : "customer" },
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