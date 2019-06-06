@extends('layouts.dashboard')

@section('title', 'Phiếu xuất')

@section('content')

    <!-- Main content -->
    <section class="content container-fluid">
        <div class="box">
            <div class="box-header">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Đơn vị xuất hàng</label>
                            <input type="text" class="form-control" name="goodTransfer[delivery_store]" value="{{ $goodTransfer->delivery_store }}" readonly>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Đơn vị nhập hàng</label>
                            <input type="text" class="form-control" name="goodTransfer[receive_store]" value="{{ $goodTransfer->receive_store }}" readonly>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Ngày</label>
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input type="text" class="form-control" value="{{ $goodTransfer->date }}" name="goodTransfer[date]" readonly>
                            </div>
                            <!-- /.input group -->
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Số phiếu</label>
                            <input type="text" class="form-control" name="goodTransfer[number]" value="{{ $goodTransfer->receive_number }}" readonly>
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
                            <th class="col-md-6">Tên sản phẩm</th>
                            <th class="col-md-3">Định mức</th>
                            <th class="col-md-3">Số lượng</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($goodTransfer->goodTransferDetails as $goodTransferDetail)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $goodTransferDetail->product->name }}</td>
                                <td>{{ $goodTransferDetail->bom->name ?? ''}}</td>
                                <td>{{ $goodTransferDetail->receive_quantity ?? ''}}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <!-- /.box-body -->
            <div class="box-footer">
                <div class="row">
                    <div class="col-md-12 text-right">
                        @role('User')
                            <button class="btn btn-primary" id="export">Xuất Excel</button>
                            @if ( $goodTransfer->status !== 5 )
                            <a href="{{ route('good-transfer.edit', $goodTransfer)}}" class="btn btn-info">
                                <i class="fa fa-pencil-square-o" aria-hidden="true"></i> Sửa
                            </a>
                            <button class="btn btn-danger" id="delete" data-toggle="modal" data-target="#modal">Xóa</button>
                            @endif
                        @endrole

                        @role('Admin')
                            @if( $goodTransfer->status === 10 )
                                <form action="{{ route('good-transfer.update', $goodTransfer) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <input type="submit" class="btn btn-success" value="Duyệt" name="approved">
                                </form>
                            @endif
                        @endrole
                    </div>
                </div>
            </div>
        </div>
        <!-- /.box -->
    </section>
    @role('User')
    <form action="{{ route('good-transfer.destroy', $goodTransfer) }}" method="POST">
        @csrf()
        @method('DELETE')
        <div id="modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h4 class="modal-title" id="custom-width-modalLabel">Xóa phiếu chuyển kho</h4>
                    </div>
                    <div class="modal-body">
                        <h5>Chắc chắn xóa phiếu {{ $goodTransfer->number }}?</h5>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default waves-effect remove-data-from-delete-form" data-dismiss="modal">Hủy</button>
                        <input type="submit" class="btn btn-danger waves-effect waves-light" value="Xóa">
                    </div>
                </div>
            </div>
        </div>
    </form>
    @endrole('User')
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
                        targets: '_all',
                        className   : 'dt-head-center'
                    }
                ]
            });

            $('button.cancel').on('click', function (e) {
                e.preventDefault();
            });

            $('#export').on('click', function () {
                $('#contract-show').table2excel();
            });

        });
    </script>
@stop