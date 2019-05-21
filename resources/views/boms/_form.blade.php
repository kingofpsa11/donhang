@extends('layouts.dashboard')

@section('title', 'Định mức')

@section('action', 'Tạo định mức')

@section('content')

    <!-- Main content -->
    <section class="content container-fluid">
        <form action="@yield('route')" method="POST" id="form">
            @csrf
            @yield('method')

            <div class="box">
                <div class="box-header">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="" class="control-label">Tên sản phẩm</label>
                                <select name="bom[product_id]" class="form-control">
                                    @if (isset( $bom ))
                                        <option value="{{ $bom->product_id }}">{{ $bom->product->name }}</option>
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="" class="control-label">Tên định mức</label>
                                <input type="text" name="bom[name]" class="form-control" value="{{ $bom->name ?? '' }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="" class="control-label">Công đoạn</label>
                                <input type="text" name="bom[stage]" class="form-control" value="{{ $bom->stage ?? '' }}">
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body table-responsive">
                    <table id="table" class="table table-bordered table-striped table-condensed">
                        <thead>
                        <tr>
                            <th>STT</th>
                            <th>Mã vật tư</th>
                            <th>Tên vật tư</th>
                            <th>Số lượng</th>
                            <th>Ghi chú</th>
                        </tr>
                        </thead>
                        <tbody>
                            @yield('table-body')
                        </tbody>
                    </table>
                </div>
                <!-- /.box-body -->
                <div class="box-footer text-right">
                    <div>
                        <button class="btn btn-primary addRow">Thêm dòng</button>
                        <input type="submit" value="Lưu" class="btn btn-success save">
                        <a href="{{ route('bom.create') }}" class="btn btn-danger cancel">Hủy</a>
                    </div>
                </div>
            </div>
            <!-- /.box -->
        </form>
    </section>
@endsection

@section('javascript')
    <script>
        $(document).ready(function () {

            let product = $('[name*="[product_id]"]');

            function getMaterial(el) {
                el.select2({
                    placeholder: 'Nhập tên sản phẩm',
                    minimumInputLength: 2,
                    ajax: {
                        url: '{{ route('product.getProduct') }}',
                        delay: 200,
                        dataType: 'json',
                        processResults: function (data) {
                            return {
                                results: $.map(data, function (item) {
                                    return {
                                        text: item.name,
                                        id: item.id,
                                        code: item.code,
                                    }
                                })
                            };
                        },
                        cache: true
                    },
                });
            }

            function getProductCode(el) {
                el.on('select2:select', function (e) {
                    let data = e.params.data;
                    let row = el.parents('tr');
                    row.find('[name*=code]').val(data.code);
                })
            }

            getMaterial(product);
            getProductCode($('tr [name*=product_id]'));

            function updateNumberOfRow() {
                let rows = $('tr[data-key]');
                rows.each(function (i, row) {
                    $(row).attr('data-key', i);
                    $(row).children('[data-col-seq="0"]').text(i + 1);
                    $(row).children('[data-col-seq="1"]').find('input').attr('name', 'bom_details[' + (i) + '][code]');
                    $(row).children('[data-col-seq="2"]').find('select').attr('name', 'bom_details[' + (i) + '][product_id]');
                    $(row).children('[data-col-seq="3"]').find('input').attr('name', 'bom_details[' + (i) + '][quantity]');
                    $(row).children('[data-col-seq="4"]').find('input').attr('name', 'bom_details[' + (i) + '][note]');
                    if (i === 0) {
                        if (rows.length === 1) {
                            $(row).find('button.removeRow').addClass('hidden');
                        } else {
                            $(row).find('button.removeRow').removeClass('hidden');
                        }
                    }
                });
            }

            //Add or remove row to table
            $('.box-footer').on('click', '.addRow:not(".disabled")',function (e) {
                e.preventDefault();
                let tableBody = $('tbody');
                let numberOfProduct = tableBody.children().length;
                let lastRow = $('tr:last');
                let newRow = lastRow.clone();
                let select2 = newRow.find('[name*=product_id]');

                newRow.attr('data-key', numberOfProduct);
                newRow.children('[data-col-seq="0"]').text(numberOfProduct + 1);
                newRow.children('[data-col-seq="1"]').find('input').attr('name', 'bom_details[' + (numberOfProduct) + '][code]');
                newRow.children('[data-col-seq="2"]').find('select').attr('name', 'bom_details[' + (numberOfProduct) + '][product_id]');
                newRow.children('[data-col-seq="3"]').find('input').attr('name', 'bom_details[' + (numberOfProduct) + '][quantity]');
                newRow.children('[data-col-seq="4"]').find('input').attr('name', 'bom_details[' + (numberOfProduct) + '][note]');
                lastRow.find('button.removeRow').removeClass('hidden');
                newRow.find('button.removeRow').removeClass('hidden');
                newRow.find('.select2-container').remove();
                newRow.find('option').remove();
                newRow.find('input').val('');
                tableBody.append(newRow);

                getMaterial(select2);
                getProductCode(select2);
            });

            $('#table').on('click', '.removeRow', function (e) {
                let currentRow = $(this).parents('tr');
                currentRow.remove();
                updateNumberOfRow();
            });

            //Click cancel button
            $('button.cancel').on('click', function (e) {
                e.preventDefault();
            });

        })
    </script>
@stop