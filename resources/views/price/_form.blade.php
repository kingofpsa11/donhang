@extends('layouts.dashboard')

@section('title', 'Giá sản phẩm')

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
                                <input type="text" class="form-control" name="code" value="{{ $product->code ?? $price->product->code ?? '' }}" readonly>
                            </div>
                            <div class="form-group">
                                <label for="" class="control-label">Tên sản phẩm</label>
                                <select class="form-control" style="width: 100%;" name="product_id" id="product_id" required>
                                    @if (isset($product))
                                        <option value="{{ $product->id }}">{{ $product->name }}</option>
                                    @elseif (isset($price))
                                        <option value="{{ $price->product_id }}">{{ $price->product->name }}</option>
                                    @endif
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="" class="control-label">Giá bán</label>
                                <input style="text-align: right;" type="text" class="form-control" name="selling_price" id="selling_price" value="{{ $price->selling_price ?? '' }}" required>
                            </div>
                            <div class="form-group">
                                <label for="" class="control-label">Ngày áp dụng</label>
                                <input style="text-align: right;" type="text" class="form-control effective_date" name="effective_date" value="{{ $price->effective_date ?? date('d/m/Y') }}" required>
                            </div>
                            <div class="form-group">
                                <label for="" class="control-label">Ghi chú</label>
                                <textarea id="" class="form-control" name="note">{{ $product->note ?? $price->note ?? '' }}</textarea>
                            </div>
                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer">
                            @if (request()->is('*create*') || request()->is('*edit*'))
                                <input type="submit" class="btn btn-success btn">
                                <input type="reset" value="Hủy" class="btn btn-warning">
                            @else
                                <a href="{{ route('prices.edit', $price) }}">Sửa</a>
                            @endif
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
            let productObj = $('#product_id');

            productObj.select2({
                placeholder: 'Nhập sản phẩm',
                minimumInputLength: 2,
                ajax: {
                    url         : '{{ route('products.get_product') }}',
                    delay       : 200,
                    dataType    : 'json',
                    processResults: function (data) {
                        return {
                            results: $.map(data, function (item) {
                                return {
                                    text    : item.name,
                                    id      : item.id,
                                    code    : item.code,
                                }
                            })
                        };
                    },
                    cache: true
                }
            });
            
            productObj.on('select2:select', function (e) {
                $('[name="code"]').val(e.params.data.code);
            });
            
            $("#selling_price").inputmask("integer", {
                groupSeparator  : '.',
                autoGroup       : true,
                removeMaskOnSubmit  : true
            });
            
            $('.effective_date').inputmask('date', {
                alias: 'dd/mm/yyyy'
            })
        });
    </script>
@stop