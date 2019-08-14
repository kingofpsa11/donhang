@extends('layouts.dashboard')

@section('title', 'Phiếu sản xuất')


@section('content')
    
    <!-- Main content -->
    <section class="content container-fluid">
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
                <div class="row hidden">
                    <div class="col-xs-6 sign-name" style="text-align: center">
                        <p>PHÒNG KẾ HOẠCH KINH DOANH</p>
                    </div>
                    <div class="col-xs-6 sign-name" style="text-align: center">
                        <p>NGƯỜI LẬP</p>
                    </div>
                </div>
                <div class="control-button">
                    <div class="text-right">
                        <a href="{{ route('manufacturer-notes.create') }}" class="btn btn-success">Tạo mới</a>
                        <a href="{{ route('manufacturer-notes.edit', $manufacturerNote) }}" class="btn btn-primary">
                            <i class="fa fa-pencil-square-o" aria-hidden="true"></i> Sửa
                        </a>
                        <button class="btn btn-default print">In</button>
                        <button class="btn btn-danger" data-toggle="modal" data-target="#modal">Xoá</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.box -->
    </section>
    <form action="{{ route('manufacturer-notes.destroy', $manufacturerNote) }}" method="POST">
        @csrf()
        @method('DELETE')
        <div id="modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h4 class="modal-title" id="custom-width-modalLabel">Xóa phiếu</h4>
                    </div>
                    <div class="modal-body">
                        <h5>Chắc chắn xóa phiếu số {{ $manufacturerNote->number }}?</h5>
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