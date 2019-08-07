@extends('layouts.dashboard')

@section('content')
    <div class="box">
        <div class="box-header">
            <h3 class="box-title">Tổng hợp LXH</h3>
            <a href="{{ route('step-notes.create') }}" class="btn btn-primary pull-right">Tạo mới</a>
        </div>
        <!-- /.box-header -->
        <div class="box-body table-responsive">
            <table id="example2" class="table table-bordered table-striped compact hover row-border" style="width:100%">
                <thead>
                <tr>
                    <th>Số LSX</th>
                    <th>Ngày</th>
                    <th>Công đoạn</th>
                    <th>Mã sản phẩm</th>
                    <th>Tên sản phẩm</th>
                    <th>Đơn vị</th>
                    <th>Số lượng</th>
                    <th>Xem</th>
                    <th>Sửa</th>
                </tr>

                </thead>
                <tbody>
                    @foreach ($stepNoteDetails as $stepNoteDetail)
                        <tr>
                            <td>{{ $stepNoteDetail->contractDetail->manufacturerOrderDetail->manufacturerOrder->number }}</td>
                            <td>{{ $stepNoteDetail->stepNote->date }}</td>
                            <td>{{ $stepNoteDetail->stepNote->step->name }}</td>
                            <td>{{ $stepNoteDetail->product->code }}</td>
                            <td>{{ $stepNoteDetail->product->name }}</td>
                            <td>{{ $stepNoteDetail->contractDetail->price->product->unit }}</td>
                            <td>{{ $stepNoteDetail->quantity }}</td>

                            <td>
                                <a href="{{ route('step-notes.show', $stepNoteDetail->step_note_id)}}" class="btn btn-success">
                                    <i class="fa fa-pencil-square-o" aria-hidden="true"></i> Xem
                                </a>
                            </td>
                            <td>
                                <a href="{{ route('step-notes.edit', $stepNoteDetail->step_note_id)}}" class="btn btn-info">
                                    <i class="fa fa-pencil-square-o" aria-hidden="true"></i> Sửa
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th>Số LSX</th>
                        <th>Ngày</th>
                        <th>Công đoạn</th>
                        <th>Tên sản phẩm</th>
                        <th>Đơn vị</th>
                        <th>Số lượng</th>
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
                $(this).html( '<input type="text" style="width:100%;" placeholder="Tìm" />' );
            } );


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
                        targets     : '_all',
                        className   : 'dt-head-center'
                    },
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