@extends('layouts.dashboard')

@section('title', 'Lệnh sản xuất')

@section('content')

    <!-- Main content -->
    <section class="content container-fluid">
        <div class="box box-default">
            <div class="box-header with-border">
                <div class="row">
                    @role(3)
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Đơn vị đặt hàng</label>
                            <input type="text" value="{{ $manufacturerOrder->contract->customer->name }}" readonly class="form-control">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Số đơn hàng</label>
                            <input type="text" value="{{ $manufacturerOrder->contract->number }}" readonly class="form-control">
                        </div>
                    </div>
                    @endrole
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Số lệnh sản xuất</label>
                            <input type="text" value="{{ $manufacturerOrder->number }}" readonly class="form-control">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Ngày đặt hàng</label>
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input type="text" class="form-control" value="{{ $manufacturerOrder->contract->date }}" readonly>
                            </div>
                            <!-- /.input group -->
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.box-header -->

            <div class="box-body table-responsive no-padding">
                <table id="example1" class="table table-bordered table-striped table-condensed">
                    <thead>
                    <tr>
                        <th class="text-center">STT</th>
                        <th class="text-center">Mã sản phẩm</th>
                        <th class="text-center">Tên sản phẩm</th>
                        <th class="text-center">Số lượng</th>
                        <th class="text-center">Tiến độ</th>
                        <th class="text-center">Trạng thái</th>
                        <th class="text-center">Ghi chú</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($manufacturerOrder->manufacturerOrderDetails as $manufacturerOrderDetail )
                        @php( $i = $loop->index )
                        <tr data-key="{{ $i }}">
                            <td class="" data-col-seq="0">{{ $loop->iteration }}</td>
                            <td data-col-seq="1">
                                {{ $manufacturerOrderDetail->contractDetail->price->product->code }}
                            </td>
                            <td data-col-seq="2">
                                {{ $manufacturerOrderDetail->contractDetail->price->product->name }}
                            </td>
                            <td data-col-seq="3">
                                {{ $manufacturerOrderDetail->contractDetail->quantity }}
                            </td>
                            <td data-col-seq="4">
                                <div class="pull-right">
                                    {{ $manufacturerOrderDetail->contractDetail->deadline }}
                                </div>
                            </td>
                            <td data-col-seq="5">
                                {{ $manufacturerOrderDetail->contractDetail->status }}
                            </td>
                            <td data-col-seq="6">
                                {{ $manufacturerOrderDetail->contractDetail->note }}
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <!-- /.box-body -->

            <div class="box-footer">
                <div class="row">
                    <div class="col-md-12 text-right">
                        @role(4)
                        <button class="btn btn-primary" id="export">Xuất Excel</button>
                        <a href="{{ route('manufacturer-notes.create', $manufacturerOrder) }}" class="btn btn-info">
                            <i class="fa fa-pencil-square-o" aria-hidden="true"></i> Tạo phiếu cắt phôi
                        </a>
                        @endrole
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('javascript')
    <script>
        {{--//Click cancel button--}}
        {{--$('button.cancel').on('click', function (e) {--}}
            {{--e.preventDefault();--}}
        {{--});--}}
        $('#export').on('click', function () {
            $('#contract-show').table2excel();
        });
    </script>
@stop
