@extends('layouts.dashboard')

@section('title', 'Giá sản phẩm')

@section('content')
    <!-- Main content -->
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Tạo giá</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div class="form-group">
                            <label for="" class="control-label">Mã sản phẩm</label>
                            <input type="text" class="form-control" name="code" value="{{ $price->product->code ?? '' }}" readonly>
                        </div>
                        <div class="form-group">
                            <label for="" class="control-label">Tên sản phẩm</label>
                            <input type="text" class="form-control" value="{{ $price->product->name }}" name="product_id" readonly>
                        </div>
                        <div class="form-group">
                            <label for="" class="control-label">Giá bán</label>
                            <input style="text-align: right;" type="text" class="form-control" name="selling_price" id="selling_price" value="{{ $price->selling_price ?? '' }}" readonly>
                        </div>
                        <div class="form-group">
                            <label for="" class="control-label">Ngày áp dụng</label>
                            <input style="text-align: right;" type="text" class="form-control" name="effective_date" value="{{ $price->effective_date ?? '' }}" readonly>
                        </div>
                        <div class="form-group">
                            <label for="" class="control-label">Ghi chú</label>
                            <textarea id="" class="form-control" name="note" readonly>{{ $product->note ?? $price->note ?? '' }}</textarea>
                        </div>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <a href="{{ route('prices.create') }}" class="btn btn-success">Tạo giá mới</a>
                        <a href="{{ route('prices.edit', $price) }}" class="btn btn-primary">Sửa</a>
                        <button class="btn btn-danger" data-toggle="modal" data-target="#modal">Xoá</button>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>
        </div>
    </section>
    @include('shared._modal', [
        'model' => $price,
        'modelName' => 'giá',
        'modelInformation' => $price->product->name,
        'routeName' => 'prices'
    ])
@endsection

@section('javascript')
    <script>
        $(function () {
            $("#selling_price").inputmask("integer", {
                groupSeparator  : '.',
                autoGroup       : true,
                removeMaskOnSubmit  : true
            });
        });
    </script>
@stop