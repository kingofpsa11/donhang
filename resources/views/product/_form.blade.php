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
                                <input type="text" class="form-control" name="code" id="code" value="{{ $product->code ?? '' }}">
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
                                <label for="" class="control-label">Đơn vị tính</label>
                                <input type="text" class="form-control" name="unit" value="{{ $product->unit ?? '' }}">
                            </div>
                            <div class="form-group">
                                <label for="" class="control-label">Ghi chú</label>
                                <textarea id="" class="form-control" name="note">{{ $product->note ?? '' }}</textarea>
                            </div>
                            <div class="form-group">
                                <label for="">Bản vẽ</label>
                                 @if (isset($product->images))
                                     <table class="table table-striped table-bordered table-hover">
                                         <tbody>
                                         @foreach($product->images as $image)
                                             <tr id="{{ $image->id }}">
                                                 <td>
                                                    <a href="{{ asset('storage/' & $image->link) }}">{{ $image->name }}</a>
                                                 </td>
                                                 <td>
                                                     <button class="btn btn-danger delete-image" data-toggle="modal" data-target="#modal" data-id="{{ $image->id }}" data-name="{{ $image->name }}">Xoá</button>
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
                            <input type="submit" class="btn btn-success btn" id="save" value="Lưu" @if(Request::is('*create')) disabled @endif>
                            <input type="reset" value="Hủy" class="btn btn-warning">
                        </div>
                    </div>
                    <!-- /.box -->
                </form>
            </div>
        </div>
    </section>
    <div id="modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title" id="custom-width-modalLabel">Xoá file</h4>
                </div>
                <div class="modal-body">
                    <h5></h5>
                    <input type="hidden" name="id" value="">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect remove-data-from-delete-form" data-dismiss="modal">Hủy</button>
                    <button type="button" class="btn btn-danger waves-effect waves-light modal-delete-image">Xoá</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('javascript')
    <script>
        $(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            let code = $('#code')
            code.on('focusout', function () {
                let codeValue = code.val();
                let saveBtn = $('#save');
                $.get(
                    "{{ route('products.exist_code') }}",
                    {code: codeValue},
                    function (result) {
                        if (result > 0 && window.location.pathname.indexOf('create') >= 0) {
                            code.parent().find('span').html('Đã tồn tại Mã sản phẩm');
                        } else {
                            code.parent().find('span').html('');
                            saveBtn.prop('disabled', false);
                        }
                    },
                    "text"
                );
            });

            $('.delete-image').on('click', function () {
                let name = $(this).data('name');
                let id = $(this).data('id');
                let modalBody = $('#modal').find('.modal-body');

                modalBody.find('h5').text(`Chắc chắn xóa file ${name}?`);
                modalBody.find('input').val(id);
            });

            $('.modal-delete-image').on('click' ,function (e) {
                e.preventDefault();
                let id = $(this).parents('#modal').find('input').val();
                $.post(
                    "{{ route('products.delete_image') }}",
                    { id: id, _method: 'DELETE' },
                    function () {
                        $('#modal').modal('hide');
                        $('table').find('tr#' + id).remove();
                    },
                    "text"
                );
            });
        });
    </script>
@stop