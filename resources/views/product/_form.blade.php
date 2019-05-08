@extends('layouts.dashboard')

@section('title', 'Sản phẩm')

@section('content')
    
    <!-- Main content -->
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <form action="@yield('route')" method="POST" id="form" enctype="multipart/form-data">
                    @csrf
                    @yield('method')
                    <div class="box box-primary">
                        <div class="box-header">
                            <h3 class="box-title">Thêm sản phẩm</h3>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <div class="form-group">
                                <label for="" class="col-md-3 control-label">Nhóm</label>
                                <select class="form-control category" style="width: 100%;" name="category_id" required>
                                    <option value="">--Chọn nhóm sản phẩm--</option>
                                    @foreach($categories as $category)
                                        @if (isset($product) && $product->category_id === $category->id)
                                            <option value="{{ $category->id }}" selected>{{ $category->name }}</option>
                                        @else
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="" class="col-md-3 control-label">Mã sản phẩm</label>
                                <input type="text" class="form-control" name="code" value="{{ $product->code ?? null }}">
                            </div>
                            <div class="form-group">
                                <label for="" class="col-md-3 control-label">Tên sản phẩm</label>
                                <input type="text" class="form-control" name="name" value="{{ $product->name ?? null }}">
                            </div>
                            <div class="form-group">
                                <label for="" class="col-md-3 control-label">Ghi chú</label>
                                <textarea id="" class="form-control" name="note"value="{{ $product->note ?? null }}"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="">File</label>
                                <input type="file" name="file[]" id="" class="form-control" multiple>
                            </div>
                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer">
                            <input type="submit" class="btn btn-success btn">
                            <input type="reset" value="Hủy" class="btn btn-warning">
                            {{--<a href="{{ route('product.create') }}" class="btn btn-danger btn">Hủy</a>--}}
                        </div>
                    </div>
                    <!-- /.box -->
                </form>
            </div>
        </div>
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