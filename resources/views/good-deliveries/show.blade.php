@extends('layouts.dashboard')

@section('title', 'Đơn hàng')

@section('content')
    <section class="content-header">
        <h1>
            Phiếu xuất kho
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('manufacturer-order.index') }}"><i class="fa fa-dashboard"></i> Danh mục LSX</a></li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content container-fluid">
        <div class="box box-default">
            <div class="box-header with-border">
                <h3 class="box-title">Phiếu xuất kho</h3>
            </div>
            <!-- /.box-header -->

            <div class="box-body">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Đơn vị xuất hàng</label>
                            <input type="text" value="{{ $outputOrder->customer->name }}" readonly class="form-control">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Số lệnh xuất hàng</label>
                            <input type="text" value="{{ $outputOrder->number }}" readonly class="form-control">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Ngày đặt hàng</label>
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input type="text" class="form-control input-sm" value="{{ $outputOrder->goodDelivery->date }}" readonly>
                            </div>
                            <!-- /.input group -->
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Số phiếu xuất</label>
                            <input type="text" value="{{ $outputOrder->goodDelivery->number }}" readonly class="form-control">
                        </div>
                    </div>
                </div>
                <!-- /.row -->
            </div>
            <!-- /.box-body -->
            <div class="box-footer">
            </div>
        </div>

        <div class="box">
            <div class="box-header">
                <h3 class="box-title">Nội dung</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body table-responsive">
                <table id="example1" class="table table-bordered table-striped table-condensed">
                    <thead>
                    <tr>
                        <th>STT</th>
                        <th class="col-md-6">Tên sản phẩm</th>
                        <th class="col-md-2">Số lượng</th>
                        <th class="col-md-2">Số lượng thực xuất</th>
                        <th class="col-md-2">Ghi chú</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($outputOrder->goodDelivery->goodDeliveryDetails as $goodDeliveryDetail)
                        @php( $i = $loop->index)
                        <tr data-key="{{ $i }}">
                            <td class="" data-col-seq="0">{{ $loop->iteration }}</td>
                            <td class="col-md-4" data-col-seq="1">
                                {{ $goodDeliveryDetail->outputOrderDetail->contractDetail->price->product->name }}
                            </td>
                            <td class="col-md-1" data-col-seq="2">
                                {{ $goodDeliveryDetail->outputOrderDetail->quantity }}
                            </td>
                            <td class="col-md-2" data-col-seq="3">
                                {{ $goodDeliveryDetail->quantity }}
                            </td>
                            <td class="col-md-1" data-col-seq="4">
                                {{ $goodDeliveryDetail->note }}
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                <div class="box-footer">
                    <div class="col-md-3 pull-right">
                        <a href="{{ route('good-delivery.edit', $outputOrder) }}" class="btn btn-primary col-md-4 pull-right">Sửa</a>
                    </div>
                </div>
            </div>
            <!-- /.box-body -->
        </div>
        <!-- /.box -->
    </section>
@endsection

@section('javascript')
    <script>
        //Click cancel button
        $('button.cancel').on('click', function (e) {
            e.preventDefault();
        });
    </script>
@stop
