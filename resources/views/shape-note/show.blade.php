@extends('layouts.dashboard')

@section('title', 'Phiếu cắt tấm')

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
                    @foreach ($shapeNote->shapeNoteDetails as $shapeNoteDetail)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $shapeNoteDetail->contractDetail->manufacturerOrderDetail->manufacturerOrder->number }}</td>
                            <td>{{ $shapeNoteDetail->manufacturerNoteDetail->product->name }}</td>
                            <td>{{ $shapeNoteDetail->product->code }}</td>
                            <td>{{ $shapeNoteDetail->product->name }}</td>
                            <td>{{ $shapeNoteDetail->quantity }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <!-- /.box-body -->
            <div class="box-footer">
                <div class="control-button text-right">
                    <div>
                        <a href="{{ route('shape-notes.create') }}" class="btn btn-success">Tạo mới</a>
                        <a href="{{ route('shape-notes.edit', $shapeNote) }}" class="btn btn-primary">
                            <i class="fa fa-pencil-square-o" aria-hidden="true"></i> Sửa
                        </a>
                        <button class="btn btn-default print">
                            <i class="fa fa-print"></i> In
                        </button>
                        <button class="btn btn-danger" data-toggle="modal" data-target="#modal">
                            <i class="fa fa-remove" aria-hidden="true"></i> Xoá
                        </button>

                        <form action="{{ route('shape-notes.update', $shapeNote) }}" method="POST" style="display: inline-block;">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-success" name="approved">
                                <i class="fa fa-check"></i> Duyệt
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.box -->
    </section>
    @include('shared._modal', [
        'model' => $shapeNote,
        'modelName' => 'Phiếu cắt tấm',
        'modelInformation' => $shapeNote->number,
        'routeName' => 'shape-notes'
    ])
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