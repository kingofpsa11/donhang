@extends('layouts.dashboard')

@section('title')
    Danh mục lệnh sản xuất
@stop

@section('content')
    <div class="box">
        <div class="box-header">
            <h3 class="box-title">Tổng hợp lệnh sản xuất</h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
            <table id="example2" class="table table-bordered table-striped compact hover row-border" style="width:100%">
                <thead>
                <tr>
                    <th>Ngày lập</th>
                    <th>LSX</th>
                    <th>Mã sản phẩm</th>
                    <th>Tên sản phẩm</th>
                    <th>Số lượng</th>
                    <th>Tiến độ</th>
                    <th>Cắt tấm</th>
                    <th>Cắt phôi</th>
                    <th>Đi mạ</th>
                    <th>Hoàn thiện</th>
                    <th>Trạng thái</th>
                    <th>Xem</th>
                </tr>

                </thead>
                <tbody>
{{--                        @php--}}
{{--                        if($manufacturerOrderDetail->contractDetail->deadline) {--}}
{{--                            $deadline = Carbon\Carbon::createFromFormat(config('app.date_format'), $manufacturerOrderDetail->contractDetail->deadline)->format('Y-m-d');--}}
{{--                            $secs = strtotime($deadline) - time();--}}
{{--                            $days = $secs/86400;--}}
{{--                            if ($manufacturerOrderDetail->contractDetail->deadline === '') {--}}
{{--                                echo "class='warning'";--}}
{{--                            } elseif ($days <= 5 && $manufacturerOrderDetail->manufacturerOrder->status===10) {--}}
{{--                                echo "class='danger' ";--}}
{{--                            } else {--}}
{{--                                echo "class='success'";--}}
{{--                            }--}}
{{--                        }--}}
{{--                        @endphp--}}
                </tbody>
                <tfoot>
                    <tr>
                        <th>Ngày lập</th>
                        <th>LSX</th>
                        <th>Mã sản phẩm</th>
                        <th>Tên sản phẩm</th>
                        <th>Số lượng</th>
                        <th>Tiến độ</th>
                        <th>Cắt tấm</th>
                        <th>Cắt phôi</th>
                        <th>Đi mạ</th>
                        <th>Hoàn thiện</th>
                        <th>Trạng thái</th>
                        <td>Xem</td>
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
                'info': true,
                'autoWidth' : true,
                'searching': true,
                "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
                "language": {
                    "info": "Từ _START_ đến _END_ trong _TOTAL_ dòng",
                    "lengthMenu" : "Hiện _MENU_ dòng"
                },
                'processing'    : true,
                'serverSide'    : true,
                'ajax'          : {
                    'url'           : '{{ route('manufacturer-orders.get_all_manufacturers') }}',
                    'dataType'      : 'json',
                    'type'          : 'GET',
                    'data'          : { _token: "{{ csrf_token() }}" }
                },
                createdRow      : function (row, data, index) {
                    let now = new Date().getTime();
                    let deadline = new Date(data['deadline']).getTime();
                    let time = (deadline - now)/86400000;

                    if (time > 5) {
                        $(row).addClass('info');
                    } else if( time <= 5 && data['status'] === 10) {
                        $(row).addClass('danger');
                    }
                },
                columns         : [
                    {
                        data: 'date'
                    },
                    {
                        data: 'number'
                    },
                    {
                        data: 'code'
                    },
                    {
                        data: 'product'
                    },
                    {
                        data: 'quantity'
                    },
                    {
                        data: 'deadline'
                    },
                    {
                        data: 'first'
                    },
                    {
                        data: 'second'
                    },
                    {
                        data: 'third'
                    },
                    {
                        data: 'fourth'
                    },
                    {
                        data: 'status'
                    },
                    {
                        data: 'view'
                    },
                ],
                columnDefs      : [
                    {
                        targets     : [0,5],
                        render      : function(data) {
                            return moment(data).format("DD/MM/YYYY");
                        },
                        className   : 'dt-body-right',
                        width       : '7%',
                    },
                    {
                        targets     : 1,
                        className   : 'dt-body-center',
                        width       : '5%'
                    },
                    {
                        targets     : 2,
                        className   : 'dt-body-center',
                    },
                    {
                        targets     : 3,
                    },
                    {
                        targets     : 4,
                        className   : 'dt-body-center',
                        width       : '5%',
                    },
                    {
                        targets     : [6,7,8,9],
                        className   : 'dt-body-center'
                    },
                    {
                        targets     : 10,
                        className   : 'dt-body-center',
                        render      : function (data) {
                            switch (data) {
                                case 10:
                                    return '<span class="label label-default">Đang chờ</span>';
                                case 9:
                                    return '<span class="label label-primary">Đã tiếp nhận</span>';
                                default:
                                    return data;
                            }
                        },
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