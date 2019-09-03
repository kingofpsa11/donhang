@extends('layouts.dashboard')

@section('title', 'Phiếu tạo phôi')

@section('content')
    
    <!-- Main content -->
    <div class="box">
        <div class="box-header">
            <h3 class="box-title">Phiếu sản xuất</h3>
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Số lệnh sản xuất</label>
                        <input type="text" class="form-control" name="manufacturerNote[number]" value="{{ $manufacturerNote->number }}" readonly>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Ngày</label>
                        <div class="input-group">
                            <div class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                            </div>
                            <input type="text" class="form-control" value="{{ $manufacturerNote->date ?? '' }}" name="manufacturerNote[date]" readonly>
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
                    <th>LSX</th>
                    <th class="text-center">Tên sản phẩm</th>
                    <th>Loại phôi</th>
                    <th>Dài</th>
                    <th>Dày</th>
                    <th>Chi vi trên</th>
                    <th>Chi vi dưới</th>
                    <th class="text-center">Số lượng</th>
                    <th class="text-center">Ghi chú</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($manufacturerNote->manufacturerNoteDetails as $manufacturerNoteDetail)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $manufacturerNoteDetail->contractDetail->manufacturerOrderDetail->manufacturerOrder->number }}</td>
                        <td>{{ $manufacturerNoteDetail->contractDetail->price->product->name }}</td>
                        <td>{{ $manufacturerNoteDetail->product->name }}</td>
                        <td>{{ $manufacturerNoteDetail->length }}</td>
                        <td>{{ $manufacturerNoteDetail->thickness }}</td>
                        <td>{{ $manufacturerNoteDetail->top_perimeter }}</td>
                        <td>{{ $manufacturerNoteDetail->bottom_perimeter }}</td>
                        <td>{{ $manufacturerNoteDetail->quantity }}</td>
                        <td>{{ $manufacturerNoteDetail->note }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <!-- /.box-body -->
        <div class="box-footer">
            <div class="control-button">
                <div class="text-right">
                    <a href="{{ route('manufacturer-notes.create') }}" class="btn btn-success">Tạo mới</a>
                    <button class="btn btn-default print">In</button>
                    @if ($manufacturerNote->status == 10)
                    <a href="{{ route('manufacturer-notes.edit', $manufacturerNote) }}" class="btn btn-primary">
                        <i class="fa fa-pencil-square-o" aria-hidden="true"></i> Sửa
                    </a>
                    <button class="btn btn-danger" data-toggle="modal" data-target="#modal">Xoá</button>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <!-- /.box -->
    @include('shared._modal', [
        'model' => $manufacturerNote,
        'modelName' => 'Phiếu tạo phôi',
        'modelInformation' => $manufacturerNote->number,
        'routeName' => 'manufacturer-notes'
    ])
@endsection

@section('javascript')
    <script>
        $(document).ready(function () {
            $('[data-mask]').inputmask();

            $('#contract-show').DataTable({
                'paging'        : false,
                'lengthChange'  : false,
                'info'          : false,
                searching       : false,
                ordering        : false,
                columnDefs: [

                ]
            });

            $('button.cancel').on('click', function (e) {
                e.preventDefault();
            });

            $('.print').on('click', function (e) {
                e.preventDefault();
                window.print();
            })
        });
    </script>
@stop