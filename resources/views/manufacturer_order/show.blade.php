@extends('layouts.dashboard')

@section('title', 'Đơn hàng')

@section('content')
    <section class="content-header">
        <h1>
            Lệnh sản xuất
            <small>Tạo đơn hàng</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('manufacturer-order.index') }}"><i class="fa fa-dashboard"></i> Danh mục LSX</a></li>
            <li class="active">Tạo LSX</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content container-fluid">
        <div class="box box-default">
            <div class="box-header with-border">
                <h3 class="box-title">Đơn hàng</h3>
            </div>
            <!-- /.box-header -->

            <div class="box-body">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Đơn vị đặt hàng</label>
                            <input type="text" value="{{ $contract->customer->name }}" readonly class="form-control">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Số đơn hàng</label>
                            <input type="text" value="{{ $contract->number }}" readonly class="form-control">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Ngày đặt hàng</label>
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input type="text" class="form-control input-sm" value="{{ $contract->date }}" readonly>
                            </div>
                            <!-- /.input group -->
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
                        <th>Tên sản phẩm</th>
                        <th>Số lượng</th>
                        <th>Tiến độ</th>
                        <th>Trạng thái</th>
                        <th>Ghi chú</th>
                        <th>Đơn vị sản xuất</th>
                        <th>Số LSX/ĐH</th>
                    </tr>
                    </thead>
                    <tbody>
                    @php($i=0)
                    @foreach ($contract->contract_details as $contract_detail)
                        <tr data-key="{{ $i }}">
                            <td class="" data-col-seq="0">{{ $i + 1 }}</td>
                            <td class="col-md-4" data-col-seq="1">
                                {{ $contract_detail->price->product->name }}
                            </td>
                            <td class="col-md-1" data-col-seq="2">
                                {{ $contract_detail->quantity }}
                            </td>
                            <td class="col-md-2" data-col-seq="3">
                                <div class="pull-right">
                                    {{ $contract_detail->deadline }}
                                </div>
                            </td>
                            <td class="col-md-1" data-col-seq="4">
                                {{ $contract_detail->status }}
                            </td>
                            <td class="col-md-2" data-col-seq="5">
                                {{ $contract_detail->note }}
                            </td>
                            <td class="col-md-1" data-col-seq="6">
                                {{ $contract_detail->manufacturerOrder->supplier_id }}
                            </td>
                            <td class="col-md-1" data-col-seq="7">
                                {{ $contract_detail->manufacturerOrder->number }}
                            </td>
                        </tr>
                        @php($i++)
                    @endforeach
                    </tbody>
                </table>
                <div class="box-footer">
                    <div class="col-md-3 pull-right">
                        <a href="{{ route('manufacturer-order.edit', $contract) }}" class="btn btn-primary col-md-4 pull-right">Sửa</a>
                    </div>
                </div>
            </div>
            <!-- /.box-body -->
        </div>
        <!-- /.box -->
    </section>
@endsection

@section('javascript')
    <script src="{{ asset('plugins/input-mask/jquery.inputmask.numeric.extensions.js') }}"></script>
    <script>

        function maskDate(obj) {
            obj.inputmask({
                'alias': 'dd/mm/yyyy'
            });
        }

        let date = $('[name="contract[date]"]');
        let deadline = $('[name$="[deadline]"]');

        maskDate(date);
        maskDate(deadline);

        //Click cancel button
        $('button.cancel').on('click', function (e) {
            e.preventDefault();
        });

        function convertDateToTimestamp(obj) {
            obj.each(function (i, el) {
                let date = $(el).val();
                $(el).inputmask('remove');
                let datePart = date.split('/');
                let newDate = new Date(datePart[2], datePart[1] - 1, datePart[0]);
                $(el).val(newDate.getTime()/1000);
            })
        }

        $('#form').on('submit', function () {
            convertDateToTimestamp($('[name$="[date]"]'));
            convertDateToTimestamp($('[name$="[deadline]"]'));
        })
    </script>
@stop
