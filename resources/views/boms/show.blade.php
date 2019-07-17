@extends('layouts.dashboard')

@section('title', 'Định mức')

@section('content')

    <!-- Main content -->
    <section class="content container-fluid">
        <div class="box">
            <div class="box-header">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="" class="control-label">Tên sản phẩm</label>
                            <input type="text" value="{{ $bom->product->name }}" class="form-control" readonly>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="" class="control-label">Tên định mức</label>
                            <input type="text" name="bom[name]" class="form-control" value="{{ $bom->name }}" readonly>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="" class="control-label">Công đoạn</label>
                            <input type="text" name="bom[stage]" class="form-control" value="{{ $bom->stage }}" readonly>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body table-responsive no-padding">
                <table class="table table-bordered table-striped hover" id="contract-show">
                    <thead>
                        <tr>
                            <th>STT</th>
                            <th>Mã vật tư</th>
                            <th>Tên vật tư</th>
                            <th>Đvt</th>
                            <th>Số lượng</th>
                            <th>Ghi chú</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php($i=1)
                        @foreach ($bom->bomDetails as $bomDetail)
                            <tr>
                                <td>{{ $i }}</td>
                                <td>{{ $bomDetail->product->code }}</td>
                                <td>{{ $bomDetail->product->name }}</td>
                                <td></td>
                                <td>{{ $bomDetail->quantity }}</td>
                                <td></td>
                            </tr>
                            @php($i++)
                        @endforeach
                    </tbody>
                </table>
            </div>
            <!-- /.box-body -->
            <div class="box-footer">
                <div class="row text-right">
                    <div class="col-md-3 pull-right">
                        <button class="btn btn-primary" id="export">Xuất Excel</button>
                        <a href="{{ route('bom.edit', [$bom])}}" class="btn btn-info">
                            <i class="fa fa-pencil-square-o" aria-hidden="true"></i> Sửa
                        </a>
                        <button class="btn btn-danger" id="delete" data-toggle="modal" data-target="#modal">Xóa</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.box -->
    </section>
    <form action="{{ route('bom.destroy', [$bom]) }}" method="POST">
        @csrf()
        @method('DELETE')
        <div id="modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h4 class="modal-title" id="custom-width-modalLabel">Xóa định mức</h4>
                    </div>
                    <div class="modal-body">
                        <h5>Chắc chắn xóa định mức của {{ $bom->product->name }}?</h5>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default waves-effect remove-data-from-delete-form" data-dismiss="modal">Hủy</button>
                        <input type="submit" class="btn btn-danger waves-effect waves-light" value="Xóa">
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@section('javascript')
    <script src="{{ asset('plugins/input-mask/jquery.inputmask.numeric.extensions.js') }}"></script>
    <script src="//cdn.rawgit.com/rainabba/jquery-table2excel/1.1.0/dist/jquery.table2excel.min.js"></script>
    <script>
        $(function () {
            $('[data-mask]').inputmask();

            let customerSelect = $('.select2.customer');
            customerSelect.select2();

            $('#contract-show').DataTable({
                'paging'        : false,
                'lengthChange'  : false,
                'info'          : false,
                searching       : false,
                ordering        : false,
                columnDefs: [
                    {
                        targets: [ 3 ],
                        render: $.fn.dataTable.render.number('.', ','),
                        className   : 'dt-body-right'
                    },
                    {
                        targets: [ 4 ],
                        className   : 'dt-body-right'
                    },
                    {
                        targets: '_all',
                        className   : 'dt-head-center'
                    }
                ]
            });

            $('button.cancel').on('click', function (e) {
                e.preventDefault();
            });

            //create manufacturer order
            // $('#manufacturer_order').on('click', function () {
            //
            // });
            $('#export').on('click', function () {
                $('#contract-show').table2excel();
            });

        });
    </script>
@stop