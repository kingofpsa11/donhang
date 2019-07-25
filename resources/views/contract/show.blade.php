@extends('layouts.dashboard')

@section('title', 'Đơn hàng')


@section('content')

    <!-- Main content -->
    <section class="content container-fluid">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">Nội dung đơn hàng</h3>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Đơn vị đặt hàng</label>
                            <input type="text" class="form-control" readonly value="{{ $contract->customer->name }}">
                        </div>
                    </div>
                    <!-- /.col -->
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Số đơn hàng</label>
                            <input type="text" class="form-control" readonly value="{{ $contract->number }}">
                        </div>
                    </div>
                <!-- /.col -->
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Ngày đặt hàng</label>
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input type="text" class="form-control" name="contract[date]" value="{{ $contract->date }}" readonly>
                            </div>
                            <!-- /.input group -->
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Giá trị đơn hàng</label>
                            <input type="text" class="form-control" value="{{ $contract->total_value }}" readonly data-inputmask="'alias': 'integer', 'autoGroup': true, 'groupSeparator': '.', 'removeMaskOnSubmit': true" data-mask>
                        </div>
                    </div>
                </div>
                <!-- /.row -->
            </div>
            <!-- /.box-header -->
            <div class="box-body table-responsive no-padding">
                <table class="table table-bordered table-striped hover" id="contract-show">
                    <thead>
                        <tr>
                            <th>STT</th>
                            <th class="col-md-1">Mã sản phẩm</th>
                            <th class="col-md-4">Tên sản phẩm</th>
                            <th class="col-md-1">Số lượng</th>
                            <th class="col-md-1">Đơn giá</th>
                            <th class="col-md-1">Tiến độ</th>
                            <th class="col-md-1">Trạng thái</th>
                            <th class="col-md-1">ĐVSX</th>
                            <th class="col-md-2">Ghi chú</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php($i=1)
                        @foreach ($contract->contractDetails as $contractDetails)
                            <tr>
                                <td>{{ $i }}</td>
                                <td>{{ $contractDetails->price->product->code }}</td>
                                <td>{{ $contractDetails->price->product->name }}</td>
                                <td>{{ $contractDetails->quantity }}</td>
                                <td>{{ $contractDetails->selling_price }}</td>
                                <td>{{ $contractDetails->deadline }}</td>
                                <td>{!! $contractDetails->status === 10 ? '<span class="label label-primary">Chờ phê duyệt</span>' : ''!!}</td>
                                <td>{{ $contractDetails->supplier->short_name }}</td>
                                <td>{{ $contractDetails->note }}</td>
                            </tr>
                            @php($i++)
                        @endforeach
                    </tbody>
                </table>
            </div>
            <!-- /.box-body -->
            <div class="box-footer">
                <div class="row">
                    <div class="col-md-12 text-right">
                        @role(3)
                        <button class="btn btn-primary" id="export">Xuất Excel</button>
                        <a href="{{ route('contracts.edit', ['contract' => $contract->id])}}" class="btn btn-info">
                            <i class="fa fa-pencil-square-o" aria-hidden="true"></i> Sửa
                        </a>
                        <button class="btn btn-danger" id="delete" data-toggle="modal" data-target="#modal">Xóa</button>
                        @endrole

                        @role(6)
                            @if($contract->status === 10)
                                <form action="{{ route('contracts.update', $contract) }}" method="POST" style="display: inline-block;">
                                    @csrf
                                    @method('PATCH')
                                    <input type="submit" value="Phê duyệt" class="btn btn-success" name="approved">
                                </form>
                            @endif
                        @endrole
                    </div>
                </div>
            </div>
        </div>
        <!-- /.box -->
    </section>
    <form action="{{ route('contracts.destroy', $contract) }}" method="POST">
        @csrf()
        @method('DELETE')
        <div id="modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h4 class="modal-title" id="custom-width-modalLabel">Xóa đơn hàng</h4>
                    </div>
                    <div class="modal-body">
                        <h5>Chắc chắn xóa đơn hàng {{ $contract->number }}?</h5>
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
                        targets: [ 4 ],
                        render: $.fn.dataTable.render.number('.', ','),
                        className   : 'dt-body-right'
                    },
                    {
                        targets: [ 5 ],
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
            // $('#manufacturer-order').on('click', function () {
            //
            // });
            $('#export').on('click', function () {
                $('#contract-show').table2excel();
            });

        });
    </script>
@stop