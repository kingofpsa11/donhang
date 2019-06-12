@extends('layouts.dashboard')

@section('title', 'Phiếu nhập kho')

@section('content')

    <!-- Main content -->
    <section class="content container-fluid">
        <div class="box">
            <div class="box-header">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Đơn vị giao hàng</label>
                            <input type="text" class="form-control" value="{{ $goodReceive->supplier->name }}" readonly="">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Người giao</label>
                            <input type="text" class="form-control" name="goodReceive[supplier]" value="{{ $goodReceive->supplier_user }}" readonly>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Ngày</label>
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input type="text" class="form-control" value="{{ $goodReceive->date }}" name="goodReceive[date]" readonly>
                            </div>
                            <!-- /.input group -->
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Số phiếu</label>
                            <input type="text" class="form-control" name="goodReceive[number]" value="{{ $goodReceive->number }}" readonly>
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
                        <th class="col-md-1">Mã sản phẩm</th>
                        <th class="col-md-5">Tên sản phẩm</th>
                        <th class="col-md-1">Đvt</th>
                        <th class="col-md-2">Định mức</th>
                        <th class="col-md-1">Kho</th>
                        <th class="col-md-1">Số lượng</th>
                        <th class="col-md-1">Số lượng thực xuất</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($goodReceive->goodReceiveDetails as $goodReceiveDetail)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $goodReceiveDetail->product->code }}</td>
                            <td>{{ $goodReceiveDetail->product->name ?? ''}}</td>
                            <td></td>
                            <td>{{ $goodReceiveDetail->bom->name ?? '' }}</td>
                            <td>{{ $goodReceiveDetail->store_id }}</td>
                            <td>{{ $goodReceiveDetail->quantity }}</td>
                            <td>{{ $goodReceiveDetail->actual_quantity }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <!-- /.box-body -->
            <div class="box-footer">
                <div class="row">
                    <div class="col-md-12 text-right">
                        {{--@role('User')--}}
                        <button class="btn btn-primary" id="export">Xuất Excel</button>
                        @if ( $goodReceive->status !== 5 )
                            <a href="{{ route('good-receive.edit', $goodReceive)}}" class="btn btn-info">
                                <i class="fa fa-pencil-square-o" aria-hidden="true"></i> Sửa
                            </a>
                            <button class="btn btn-danger" id="delete" data-toggle="modal" data-target="#modal">Xóa</button>
                        @endif
                        {{--@endrole--}}

                        {{--@role('Admin')--}}
                        @if( $goodReceive->status === 10 )
                            <form action="{{ route('good-receive.update', $goodReceive) }}" method="POST" class="btn">
                                @csrf
                                @method('PATCH')
                                <input type="submit" class="btn btn-success" value="Duyệt" name="approved">
                            </form>
                        @endif
                        {{--@endrole--}}
                    </div>
                </div>
            </div>
        </div>
        <!-- /.box -->
    </section>
{{--    @role('User')--}}
    <form action="{{ route('good-receive.destroy', $goodReceive) }}" method="POST">
        @csrf()
        @method('DELETE')
        <div id="modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h4 class="modal-title" id="custom-width-modalLabel">Xóa phiếu nhập kho</h4>
                    </div>
                    <div class="modal-body">
                        <h5>Chắc chắn xóa phiếu {{ $goodReceive->number }}?</h5>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default waves-effect remove-data-from-delete-form" data-dismiss="modal">Hủy</button>
                        <input type="submit" class="btn btn-danger waves-effect waves-light" value="Xóa">
                    </div>
                </div>
            </div>
        </div>
    </form>
{{--    @endrole('User')--}}
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