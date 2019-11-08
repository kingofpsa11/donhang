@extends('layouts.dashboard')

@section('title', 'Chi phí cột thép')

@section('content')
    <div class="box">
        <div class="box-header">
            <a href="{{ route('expense-of-pole.create') }}" class="btn btn-primary pull-right">Tạo mới</a>
        </div>
        <!-- /.box-header -->
        <div class="box-body table-responsive">
            <table id="example2" class="table table-bordered table-striped compact hover row-border" style="width:100%">
                <thead>
                    <tr>
                        <th>Chủng loại</th>
                        <th>Giá thép</th>
                        <th>Dự phòng vật tư</th>
                        <th>Chi phí vật tư</th>
                        <th>Hao phí cột</th>
                        <th>Chi phí nhân công trực tiếp</th>
                        <th>Chi phí chung</th>
                        <th>Chi phí mạ kẽm</th>
                        <th>Chi phí vận chuyển</th>
                        <th>Lãi</th>
                        <th>Đơn giá</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($expenses as $expense)
                        <tr>
                            <td>{{ $expense->name }}</td>
                            <td>{{ $expense->gia_thep }}</td>
                            <td>{{ $expense->du_phong_vat_tu }} %</td>
                            <td>{{ $expense->vat_tu_phu }} %</td>
                            <td>{{ $expense->hao_phi }} %</td>
                            <td>{{ $expense->nhan_cong_truc_tiep }}</td>
                            <td>{{ $expense->chi_phi_chung }} %</td>
                            <td>{{ $expense->chi_phi_ma_kem }}</td>
                            <td>{{ $expense->chi_phi_van_chuyen }}</td>
                            <td>{{ $expense->lai }} %</td>
                            <td>{{ $expense->don_gia }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th>Chủng loại</th>
                        <th>Giá thép</th>
                        <th>Dự phòng vật tư</th>
                        <th>Chi phí vật tư</th>
                        <th>Hao phí cột</th>
                        <th>Chi phí nhân công trực tiếp</th>
                        <th>Chi phí chung</th>
                        <th>Chi phí mạ kẽm</th>
                        <th>Chi phí vận chuyển</th>
                        <th>Lãi</th>
                        <th>Đơn giá</th>
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
                "lengthMenu": [[25, 50, -1], [25, 50, "All"]],
                "language": {
                    "info": "Từ _START_ đến _END_ trong _TOTAL_ dòng",
                    "lengthMenu" : "Hiện _MENU_ dòng"

                },
                columnDefs: [
                    {
                        targets     : 0,
                        className   : 'dt-body-left'
                    },
                    {
                        targets     : [1,5,7,8,10],
                        className   : 'dt-body-right',
                        render: $.fn.dataTable.render.number( '.', ',')
                    },
                    {
                        targets     : [2,3,4,6,9],
                        render: $.fn.dataTable.render.number( '.', ',',2,'',' %')
                    },
                    {
                        targets     : '_all',
                        className   : 'dt-head-center dt-body-center',
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
