@extends('layouts.dashboard')

@section('content')
    <div class="box">
        <div class="box-header">
            <h3 class="box-title">Tổng hợp LXH</h3>
            <a href="{{ route('shape-notes.create') }}" class="btn btn-primary pull-right">Tạo mới</a>
        </div>
        <!-- /.box-header -->
        <div class="box-body table-responsive">
            <table id="example2" class="table table-bordered table-striped compact hover row-border" style="width:100%">
                <thead>
                <tr>
                    <th>STT</th>
                    <th>Tên sản phẩm</th>
                    <th>Mã vật tư</th>
                    <th>Tên vật tư</th>
                    <th>Số lượng</th>
                    <th>Xem</th>
                    <th>Sửa</th>
                </tr>

                </thead>
                <tbody>
                    @foreach ($shapeNoteDetails as $shapeNoteDetail)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $shapeNoteDetail->contractDetail->manufacturerOrderDetail->manufacturerOrder->number }}</td>
                            <td>{{ $shapeNoteDetail->manufacturerNoteDetail->product->name }}</td>
                            <td>{{ $shapeNoteDetail->product->code }}</td>
                            <td>{{ $shapeNoteDetail->product->name }}</td>
                            <td>{{ $shapeNoteDetail->quantity }}</td>
                            <td>
                                <a href="{{ route('shape-notes.show', $shapeNoteDetail->shape_note_id)}}" class="btn btn-success">
                                    <i class="fa fa-pencil-square-o" aria-hidden="true"></i> Xem
                                </a>
                            </td>
                            <td>
                                <a href="{{ route('shape-notes.edit', $shapeNoteDetail->shape_note_id)}}" class="btn btn-info">
                                    <i class="fa fa-pencil-square-o" aria-hidden="true"></i> Sửa
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th>STT</th>
                        <th>Tên sản phẩm</th>
                        <th>Mã vật tư</th>
                        <th>Tên vật tư</th>
                        <th>Số lượng</th>
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