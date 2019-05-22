@extends('layouts.dashboard')

@section('content')
    <div class="box">
        <div class="box-header">
            <h3 class="box-title">Tổng hợp Phiếu sản xuất</h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
            <table id="example2" class="table table-bordered table-striped compact hover row-border" style="width:100%">
                <thead>
                <tr>
                    <th>Lệnh sản xuất</th>
                    <th>Số phiếu</th>
                    <th>Tên sản phẩm</th>
                    <th>Số lượng</th>
                    <th>Ghi chú</th>
                    <th>Action</th>
                </tr>

                </thead>
                <tbody>
                @foreach ($manufacturerNoteDetails as $manufacturerNoteDetail)
                    <tr>
                        <td>{{ $manufacturerNoteDetail->contractDetail->manufacturerOrder->number }}</td>
                        <td>{{ $manufacturerNoteDetail->manufacturerNote->number }}</td>
                        <td>{{ $manufacturerNoteDetail->contractDetail->price->product->name }}</td>
                        <td>{{ $manufacturerNoteDetail->quantity }}</td>
                        <td>{{ $manufacturerNoteDetail->note }}</td>
                        <td>
                            <div class="btn-group">
                                <a href="{{ route('manufacturer-note.show', [$manufacturerNoteDetail->manufacturer_note_id])}}" class="btn btn-success btn-xs">
                                    <i class="fa fa-pencil-square-o" aria-hidden="true"></i> Xem
                                </a>
                                <a href="{{ route('manufacturer-note.edit', [$manufacturerNoteDetail->manufacturer_note_id])}}" class="btn btn-info btn-xs">
                                    <i class="fa fa-pencil-square-o" aria-hidden="true"></i> Sửa
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforeach

                </tbody>
                <tfoot>
                    <tr>
                        <th>Lệnh sản xuất</th>
                        <th>Số phiếu</th>
                        <th>Tên sản phẩm</th>
                        <th>Số lượng</th>
                        <th>Ghi chú</th>
                        <td></td>
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