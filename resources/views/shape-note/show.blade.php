@extends('layouts.dashboard')

@section('title', 'Phiếu công đoạn')

@section('content')
    
    <!-- Main content -->
    <section class="content container-fluid">
        <div class="box">
            <div class="box-header with-border">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Số phiếu</label>
                            <input type="text" class="form-control" name="number" value="{{ $shapeNote->number ?? '' }}" readonly>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Ngày</label>
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input type="text" class="form-control"  name="date" value="{{ $shapeNote->date ?? date('d/m/Y') }}" readonly>
                            </div>
                            <!-- /.input group -->
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
                        <th class="col-md-1">Số LSX</th>
                        <th class="col-md-4">Tên sản phẩm</th>
                        <th class="col-md-1">Mã vật tư</th>
                        <th class="col-md-4">Tên vật tư</th>
                        <th class="col-md-2">Số lượng</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($shapeNote->shapeNoteDetail as $shapeNoteDetail)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $shapeNoteDetail->contractDetail->manufacturerOrderDetail->manufacturerOrder->number }}</td>
                            <td>{{ $shapeNoteDetail->contractDetail->price->product->code }}</td>
                            <td>{{ $shapeNoteDetail->contractDetail->price->product->name }}</td>
                            <td>{{ $shapeNoteDetail->quantity }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <!-- /.box-body -->
            <div class="box-footer">
                <div class="row hidden">
                    <div class="col-xs-6 sign-name" style="text-align: center">
                        <p>PHÒNG KẾ HOẠCH KINH DOANH</p>
                    </div>
                    <div class="col-xs-6 sign-name" style="text-align: center">
                        <p>NGƯỜI LẬP</p>
                    </div>
                </div>
                <div class="control-button text-right">
                    <div>
                        <a href="{{ route('step-notes.create') }}" class="btn btn-success">Tạo mới</a>
                        <a href="{{ route('step-notes.edit', $shapeNote) }}" class="btn btn-primary">
                            <i class="fa fa-pencil-square-o" aria-hidden="true"></i> Sửa
                        </a>
                        <button class="btn btn-default print">In</button>
                        {{--<button class="btn btn-danger" data-toggle="modal" data-target="#modal">Xoá</button>--}}

                        <form action="{{ route('step-notes.update', $shapeNote) }}" method="POST" style="display: inline-block;">
                            @csrf
                            @method('PATCH')
                            <input type="submit" value="Duyệt" class="btn btn-success" name="approved">
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.box -->
    </section>
    {{--@include('shared._modal', [--}}
        {{--'model' => $shapeNote,--}}
        {{--'modelName' => 'Phiếu công đoạn',--}}
        {{--'modelInformation' => $shapeNote->number,--}}
        {{--'routeName' => 'step-notes'--}}
    {{--])--}}
@endsection

@section('javascript')
    <script>
        $(document).ready(function () {
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
                        targets: [ 2 ],
                        render: $.fn.dataTable.render.number('.', ','),
                        className   : 'dt-body-right'
                    },
                    {
                        targets: [ 3 ],
                        className   : 'dt-body-right'
                    }
                ]
            });

            $('button.cancel').on('click', function (e) {
                e.preventDefault();
            });

            $(window).on('beforeprint', function () {

            });

            $('.print').on('click', function (e) {
                e.preventDefault();
                window.print();
            })
        });
    </script>
@stop