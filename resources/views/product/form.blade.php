@extends('layouts.dashboard')

@section('title', 'Đơn hàng')

@section('content')
    <section class="content-header">
        <h1>
            Tạo sản phẩm
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('product.index') }}"><i class="fa fa-dashboard"></i> Danh mục sản phẩm</a></li>
            <li class="active">Tạo sản phẩm</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <form action="@yield('route')" method="POST" id="form">
                    @csrf
                    @yield('method')
                    <div class="box box-primary">
                        <div class="box-header">
                            <h3 class="box-title">Thêm sản phẩm</h3>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            @yield('form-content')
                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer">
                            <button type="submit" class="btn btn-success btn-lg">Lưu</button>
                            <a href="{{ route('product.index') }}" class="btn btn-danger btn-lg">Hủy</a>
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

        function maskDate(obj) {
            obj.inputmask({
                'alias': 'dd/mm/yyyy'
            });
        }

        function updateNumberOfRow() {
            let rows = $('tr[data-key]');
            rows.each(function (i, row) {
                $(row).attr('data-key', i);
                $(row).children('[data-col-seq="0"]').text(i + 1);
                $(row).children('[data-col-seq="1"]').find('input').attr('name', 'product' + (i) + '][category_id]');
                $(row).children('[data-col-seq="2"]').find('input').attr('name', 'product' + (i) + '][code]');
                $(row).children('[data-col-seq="3"]').find('input').attr('name', 'product' + (i) + '][name]');
                $(row).children('[data-col-seq="4"]').find('input').attr('name', 'product' + (i) + '][note]');
            });
        }

        //Add or remove row to table
        $('.addRow').on('click', function (e) {
            e.preventDefault();
            let tableBody = $('tbody');
            let numberOfProduct = tableBody.children().length;
            let lastRow = $('tr:last');
            let newRow = lastRow.clone();

            newRow.attr('data-key', numberOfProduct);
            newRow.children('[data-col-seq="0"]').text(numberOfProduct + 1);
            newRow.children('[data-col-seq="1"]').find('input').attr('name', 'product[' + (numberOfProduct) + '][category_id]');
            newRow.children('[data-col-seq="2"]').find('input').attr('name', 'product[' + (numberOfProduct) + '][code]');
            newRow.children('[data-col-seq="3"]').find('input').attr('name', 'product[' + (numberOfProduct) + '][name]');
            newRow.children('[data-col-seq="4"]').find('input').attr('name', 'product[' + (numberOfProduct) + '][note]');
            newRow.find('input').val('');
            tableBody.append(newRow);
        });

        $('#example1').on('click', '.removeRow', function (e) {
            let currentRow = $(this).parents('tr');
            currentRow.remove();
            updateNumberOfRow();
        });

        //Click cancel button
        $('button.cancel').on('click', function (e) {
            e.preventDefault();
        });

    </script>
@stop