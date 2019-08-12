@extends('layouts.dashboard')

@section('title', 'Sản phẩm')


@section('content')
    <!-- Main content -->
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Thêm sản phẩm</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div class="form-group">
                            <label for="" class="col-md-3 control-label">Nhóm</label>
                            <input type="text" class="form-control" value="{{ $product->category->name }}" readonly>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-md-3 control-label">Mã sản phẩm</label>
                            <input type="text" class="form-control" value="{{ $product->code }}" readonly>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-md-3 control-label">Tên sản phẩm</label>
                            <input type="text" class="form-control" value="{{ $product->name }}" readonly>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-md-3 control-label">Tên sản phẩm hoá đơn</label>
                            <input type="text" class="form-control" value="{{ $product->name_bill }}" readonly>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-md-3 control-label">Ghi chú</label>
                            <textarea id="" class="form-control" readonly>{{ $product->note }}</textarea>
                        </div>
                        <div class="form-group">
                            @if (isset($product->images))
                                @foreach (($product->images) as $image)
                                    <div class="btn btn-default">
                                        <a href="{{ asset('storage/' . $image->link) }}" download>
                                            <span class="glyphicon glyphicon-download"></span> {{ $image->name }}
                                        </a>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                        <div class="box-footer">
                            <a href="{{ route('products.create') }}" class="btn btn-success">Tạo mới</a>
                            <a href="{{ route('products.edit', $product) }}" class="btn btn-primary">Sửa</a>
                            <button class="btn btn-danger" id="delete" data-toggle="modal" data-target="#modal">Xóa</button>
                            <a href="{{ route('prices.create', $product) }}" class="btn btn-warning">Tạo giá</a>
                        </div>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>
        </div>
    </section>
    <form action="{{ route('products.destroy', $product) }}" method="POST">
        @csrf()
        @method('DELETE')
        <div id="modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h4 class="modal-title" id="custom-width-modalLabel">Xóa sản phẩm</h4>
                    </div>
                    <div class="modal-body">
                        <h5>Chắc chắn xóa sản phẩm {{ $product->name }}?</h5>
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