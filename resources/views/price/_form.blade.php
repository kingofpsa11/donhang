@extends('layouts.dashboard')

@section('title', 'Giá')

@section('content')
    
    <!-- Main content -->
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <form action="@yield('route')" method="POST" id="form">
                    @csrf
                    @yield('method')
                    <div class="box box-primary">
                        <div class="box-header">
                            <h3 class="box-title">Tạo giá</h3>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <div class="form-group">
                                <label for="" class="control-label">Mã sản phẩm</label>
                                <input type="text" class="form-control" name="code" value="{{ $product->code ?? '' }}" readonly>
                            </div>
                            <div class="form-group">
                                <label for="" class="control-label">Tên sản phẩm</label>
                                <select class="form-control" style="width: 100%;" name="product_id" id="product_id" required>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="" class="control-label">Giá bán</label>
                                <input style="text-align: right;" type="text" class="form-control" name="selling_price" id="selling_price" value="{{ $product->selling_price ?? '' }}" required>
                            </div>
                            <div class="form-group">
                                <label for="" class="control-label">Ghi chú</label>
                                <textarea id="" class="form-control" name="note">
                                    {{ $product->note ?? '' }}
                                </textarea>
                            </div>
                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer">
                            <input type="submit" class="btn btn-success btn">
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
        $(document).ready(function () {
            $('#product_id').select2({
                placeholder: 'Nhập sản phẩm',
                minimumInputLength: 2,
                ajax: {
                    url         : '{{ route('product.getProduct') }}',
                    delay       : 200,
                    dataType    : 'json',
                    processResults: function (data) {
                        return {
                            results: $.map(data, function (item) {
                                return {
                                    text: item.name,
                                    id: item.id,
                                }
                            })
                        };
                    },
                    cache: true
                }
            });

            $("#selling_price").inputmask("integer", {
                numericInput    : true,
                groupSeparator  : ',',
                autoGroup       : true
            });
        });
    </script>
@stop