@extends('layouts.dashboard')

@section('title', 'Lệnh sản xuất')

@section('content')
    <!-- Main content -->
    <section class="content container-fluid">
        <form action="@yield('route')" method="POST" id="form">
            @csrf
            @yield('method')
            <div class="box box-default">
                <div class="box-header with-border">
                    <h3 class="box-title">Lệnh sản xuất</h3>
                </div>
                <!-- /.box-header -->

                <div class="box-body">
                    <div class="row">
                        <div class="col-md-6">
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
                                    <input type="text" class="form-control" value="{{ $contract->date }}" readonly>
                                </div>
                                <!-- /.input group -->
                            </div>
                        </div>
                    </div>
                    <!-- /.row -->
                </div>
                <!-- /.box-body -->
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
                            <th class="col-md-1">Mã sản phẩm</th>
                            <th class="col-md-5">Tên sản phẩm</th>
                            <th class="col-md-1">Số lượng</th>
                            <th class="col-md-1">Tiến độ</th>
                            <th class="col-md-2">Ghi chú</th>
                            <th class="col-md-2">Đơn vị sản xuất</th>
                            @if (isset($contract_detail->manufacturerOrder->number))
                                <th>Số LSX/ĐH</th>
                            @endif

                        </tr>
                        </thead>
                        <tbody>
                            @yield('table-body')
                        </tbody>
                    </table>
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                    <div class="col-md-12 text-right">
                        <input type="submit" value="Lưu" class="btn btn-success save">
                        <a href="{{ route('manufacturer-order.show', [$contract]) }}" class="btn btn-danger cancel">Hủy</a>
                    </div>
                </div>
            </div>
            <!-- /.box -->
        </form>
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