@extends('layouts.dashboard')

@section('content')
    <div class="box">
        <div class="box-header">
            <h3 class="box-title">Tổng hợp đơn hàng</h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
            <table id="example2" class="table table-bordered table-striped compact hover row-border">
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
                    <th>Action</th>
                </tr>

                </thead>
                <tbody>
                @foreach ($contract_details as $contract_detail)
                    <tr>
                        <td>{{ $contract_detail->contract->customer->short_name }}</td>
                        <td>{{ $contract_detail->contract->number }}</td>
                        <td>{{ $contract_detail->price->product->name }}</td>
                        <td>{{ $contract_detail->quantity }}</td>
                        <td>{{ $contract_detail->selling_price }}</td>
                        <td>{{ $contract_detail->contract->date }}</td>
                        <td>{{ $contract_detail->deadline }}</td>
                        <td>{{ $contract_detail->manufacturer_order_number }}</td>
                        <td>{{ $contract_detail->status }}</td>
                        <td style="width: 10%;">
                            <div class="btn-group">
                                <a href="{{ route('contract.show', ['contract' => $contract_detail->contract_id])}}" class="btn btn-success btn-sm">
                                    <i class="fa fa-pencil-square-o" aria-hidden="true"></i> Xem
                                </a>
                                <a href="{{ route('contract.edit', ['contract' => $contract_detail->contract_id])}}" class="btn btn-info btn-sm">
                                    <i class="fa fa-pencil-square-o" aria-hidden="true"></i> Sửa
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforeach

                </tbody>
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
                var title = $(this).text();
                $(this).html( '<input type="text" style="width:100%;" placeholder="Search '+title+'" />' );
            } );


            let table = $('#example2').DataTable({
                'paging': true,
                'ordering': true,
                'info': true,
                'autoWidth' : true,
                "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
                "language": {
                    "info": "Từ _START_ đến _END_ trong _TOTAL_ dòng",
                    "lengthMenu" : "Hiện _MENU_ dòng"

                },
                "columns" : [
                    { "data" : "customer" },
                    { "data" : "number" },
                    { "data" : "product" },
                    { "data" : "quantity" },
                    {
                        "data"      : "selling_price",
                        render      : $.fn.dataTable.render.number( ',', '.'),
                        className   : 'dt-body-right'
                    },
                    {
                        "data" : "date",
                        className   : 'dt-body-right'
                    },
                    {
                        "data"      : "deadline",
                        className   : 'dt-body-right'
                    },
                    { "data" : "order" },
                    {
                        "data"      : "status",
                        "render"    : function (data) {
                            if (data == 10) {
                                return '<span class="label label-warning">Đang sản xuất</span>';
                            } else if (data == 0) {
                                return '<span class="label label-success">Xong</span>';
                            }
                        }
                    },
                    {
                        "data" : "action",
                        "className" : 'dt-body-right',
                    }
                ],
                columnDefs: [
                    {
                        targets: "_all",
                        className   : 'dt-head-center'
                    },
                    // {
                    //     targets: [5,6],
                    //     render: function(data) {
                    //         return moment(data).format("DD-MM-YYYY");
                    //     }
                    // }
                ]
            });

            table.columns().every( function () {
                var that = this;

                $( 'input', this.footer() ).on( 'keyup change', function () {
                    console.log(that.search());
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