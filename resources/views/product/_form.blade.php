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
                            <h3 class="box-title">Tạo sản phẩm</h3>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <div class="form-group">
                                <label for="" class="control-label">Nhóm</label>
                                <select class="form-control category" style="width: 100%;" name="category_id" required>
                                    <option hidden>--Chọn nhóm sản phẩm--</option>
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
                                <label for="" class="control-label">Mã sản phẩm</label>
                                <input type="text" class="form-control" name="code" value="{{ $product->code ?? '' }}">
                                <span class="text-red check-code"></span>
                            </div>
                            <div class="form-group">
                                <label for="" class="control-label">Tên sản phẩm</label>
                                <input type="text" class="form-control" name="name" value="{{ $product->name ?? '' }}">
                            </div>
                            <div class="form-group">
                                <label for="" class="control-label">Tên sản phẩm hoá đơn</label>
                                <input type="text" class="form-control" name="name_bill" value="{{ $product->name_bill ?? '' }}">
                            </div>
                            <div class="form-group">
                                <label for="" class="control-label">Ghi chú</label>
                                <textarea id="" class="form-control" name="note">{{ $product->note ?? '' }}</textarea>
                            </div>
                            <div class="form-group">
                                <label for="">Bản vẽ</label>
                                 @if ($product->file)
                                     <table class="table table-striped table-bordered table-hover">
                                         <tbody>
                                         @foreach(json_decode($product->file) as $file)
                                             <tr>
                                                 <td>
                                                    <a href="{{ asset('storage/' & $file) }}">{{ $file }}</a>
                                                 </td>
                                                 <td>
                                                     <button class="btn btn-danger" data-toggle="modal" data-target="#{{ $file }}">Xoá</button>
                                                     <form action="{{ route('products.delete_file', $file) }}" method="POST">
                                                         @csrf()
                                                         @method('DELETE')
                                                         <div id="{{ $file }}" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
                                                             <div class="modal-dialog modal-md">
                                                                 <div class="modal-content">
                                                                     <div class="modal-header">
                                                                         <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                                                         <h4 class="modal-title" id="custom-width-modalLabel">Xóa sản phẩm</h4>
                                                                     </div>
                                                                     <div class="modal-body">
                                                                         <h5>Chắc chắn xóa file {{ $file }}?</h5>
                                                                     </div>
                                                                     <div class="modal-footer">
                                                                         <button type="button" class="btn btn-default waves-effect remove-data-from-delete-form" data-dismiss="modal">Hủy</button>
                                                                         <input type="submit" class="btn btn-danger waves-effect waves-light" value="Xóa">
                                                                     </div>
                                                                 </div>
                                                             </div>
                                                         </div>
                                                     </form>
                                                 </td>
                                             </tr>
                                         @endforeach
                                         </tbody>
                                     </table>
                                 @endif
                                <input type="file" name="file[]" id="" class="form-control" multiple>
                            </div>
                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer">
                            <input type="submit" class="btn btn-success btn" value="Lưu">
                            <input type="reset" value="Hủy" class="btn btn-warning">
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
        $('#form').on('submit', function (e) {
            e.preventDefault();
            let form = this;
            let codeObj = $('[name*="code"]');
            let code = codeObj.val();
            codeObj.parent().find('span').html('');
        
            $.get(
                "{{ route('products.exist_code') }}",
                {code: code},
                function (result) {
                    if (result > 0 && window.location.pathname.indexOf('create') >= 0) {
                        codeObj.parent().find('span').html('Đã tồn tại Mã sản phẩm');
                    } else {
                        form.submit();
                    }
                },
                "text"
            );
        });
    </script>
@stop