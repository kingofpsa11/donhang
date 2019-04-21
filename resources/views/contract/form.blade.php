@extends('layouts.dashboard')

@section('title', 'Đơn hàng')

@section('content')
    <section class="content-header">
        <h1>
            Đơn hàng
            <small>Tạo đơn hàng</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('contract.index') }}"><i class="fa fa-dashboard"></i> Danh mục đơn hàng</a></li>
            <li class="active">Tạo đơn hàng</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content container-fluid">
        <form action="@yield('route')" method="POST">
            @csrf
            @yield('method')
            <div class="box box-default">
                <div class="box-header with-border">
                    <h3 class="box-title">Đơn hàng</h3>

                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        {{--<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>--}}
                    </div>
                </div>
                <!-- /.box-header -->

                <div class="box-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Đơn vị đặt hàng</label>
                                <select class="form-control input-sm select2 customer" name="contract[customer_id]">
                                    <option>--Lựa chọn đơn vị đặt hàng--</option>
                                    @foreach ($customers as $customer)
                                        <option value="{{ $customer->id }}">{{ $customer->short_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        @yield('contract-number')
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Ngày đặt hàng</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input type="text" class="form-control input-sm" value="@yield('contract-date')" name="contract[date]">
                                </div>
                                <!-- /.input group -->
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Giá trị đơn hàng</label>
                                <input type="text" class="form-control input-sm" readonly name="contract[total_value]" value="@yield('contract-total-value')">
                            </div>
                        </div>
                    </div>
                    <!-- /.row -->
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                </div>
            </div>

            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Nội dung</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body table-responsive">
                    <table id="example1" class="table table-bordered table-striped table-condensed">
                        <thead>
                        <tr>
                            <th>STT</th>
                            <th>Tên sản phẩm</th>
                            <th>Số lượng</th>
                            <th>Đơn giá</th>
                            <th>Tiến độ</th>
                            <th>Ghi chú</th>
                        </tr>
                        </thead>
                        <tbody>
                            @yield('table-body')
                        </tbody>
                    </table>
                    <div class="box-footer">
                        <div class="col-md-2 pull-right">
                            <input type="submit" value="Lưu" class="btn btn-success save col-md-6">
                            <a href="{{ url('/') }}" class="btn btn-danger col-md-6 cancel">Hủy</a>
                        </div>
                    </div>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </form>
    </section>
@endsection

@section('javascript')
    <script src="{{ asset('plugins/input-mask/jquery.inputmask.numeric.extensions.js') }}"></script>
    <script>

        let customerSelect = $('.select2.customer');
        customerSelect.select2();

        $('tbody').on('change', '[name$="[quantity]"]', calculateTotal);

        function calculateTotal() {
            let rows = $('tr[data-key]');
            let total_value = 0;
            rows.each(function (i, el) {
                let selling_price = $(el).find('[name$="[selling_price]"]').val().replace(/(\d+).(?=\d{3}(\D|$))/g, "$1");
                console.log(selling_price);
                let quantity = $(el).find('[name$="[quantity]"]').val();
                console.log(quantity);
                total_value += selling_price * quantity;
            });

            $('[name$="[total_value]"]').val(total_value);
        }

        function maskCurrency(obj) {
            obj.inputmask({
                alias: 'integer',
                autoGroup: true,
                groupSeparator: '.'
            });
        }

        function maskDate(obj) {
            obj.inputmask({
                'alias': 'dd/mm/yyyy'
            });
        }

        let total_value = $('[name="contract[total_value]"]');
        let selling_price = $('[name$="[selling_price]"]');
        let date = $('[name="contract[date]"]');
        let deadline = $('[name$="[deadline]"]');

        maskCurrency(total_value);
        maskCurrency(selling_price);
        maskDate(date);
        maskDate(deadline);

        function convertNumber(obj) {
            obj.inputmask('remove');
            obj.val(obj.val().replace(/(\d+).(?=\d{3}(\D|$))/g, "$1"));
        }

        $('form').on('submit', function () {
            convertNumber(selling_price);
            convertNumber(total_value);
        });

        function addSelect2 (el) {
            el.select2({
                placeholder: 'Nhập tên sản phẩm',
                minimumInputLength: 2,
                ajax: {
                    url: '{{ route('prices.shows') }}',
                    delay: 200,
                    dataType: 'json',
                    dropdownAutoWidth : true,
                    processResults: function (data) {
                        return {
                            results: $.map(data, function (item) {
                                return {
                                    text: item.name,
                                    id: item.id,
                                    selling_price: item.selling_price,
                                }
                            })
                        };
                    },
                    cache: true
                },
            });
        }

        function getPrice (el) {
            el.on('select2:select', function (e) {
                let data = e.params.data;
                $(this).parents('tr').find('input[name$="[selling_price]"]').val(data.selling_price);
                $(this).parents('tr').find('input[name$="[price_id]"]').val(data.id);
                calculateTotal();
            });
        }

        let priceSelect = $('.select2.price');

        addSelect2(priceSelect);
        getPrice(priceSelect);

        function updateNumberOfRow() {
            let rows = $('tr[data-key]');
            rows.each(function (i, row) {
                row.attr('data-key', i);
                row.children('[data-col-seq="0"]').text(i + 1);
                row.children('[data-col-seq="1"]').find('input').attr('name', 'contract_detail[' + (i) + '][product_id]');
                row.children('[data-col-seq="2"]').find('input').attr('name', 'contract_detail[' + (i) + '][quantity]');
                row.children('[data-col-seq="3"]').find('input').attr('name', 'contract_detail[' + (i) + '][selling_price]');
                row.children('[data-col-seq="4"]').find('input').attr('name', 'contract_detail[' + (i) + '][deadline]');
                row.children('[data-col-seq="5"]').find('input').attr('name', 'contract_detail[' + (i) + '][note]');
            });
        }

        //Add or remove row to table
        $('#example1').on('click', '.addProduct', function (e) {
            e.preventDefault();
            let icon = $(this).children('i');
            let tableBody = $('tbody');
            let numberOfProduct = tableBody.children().length;
            let lastRow = $('tr:last');
            let newRow = lastRow.clone();
            let select2 = newRow.find('.select2.price');

            if (icon.hasClass('fa-plus')) {
                newRow.attr('data-key', numberOfProduct);
                newRow.children('[data-col-seq="0"]').text(numberOfProduct + 1);
                newRow.children('[data-col-seq="1"]').find('input').attr('name', 'contract_detail[' + (numberOfProduct) + '][product_id]');
                newRow.children('[data-col-seq="2"]').find('input').attr('name', 'contract_detail[' + (numberOfProduct) + '][quantity]');
                newRow.children('[data-col-seq="3"]').find('input').attr('name', 'contract_detail[' + (numberOfProduct) + '][selling_price]');
                newRow.children('[data-col-seq="4"]').find('input').attr('name', 'contract_detail[' + (numberOfProduct) + '][deadline]');
                newRow.children('[data-col-seq="5"]').find('input').attr('name', 'contract_detail[' + (numberOfProduct) + '][note]');
                lastRow.children('[data-col-seq="6"]').find('.addProduct i').removeClass('fa-plus').addClass('fa-minus');
                console.log(newRow.find('.select2-container'));
                newRow.find('.select2-container').remove();
                newRow.find('option').remove();
                newRow.find('input').val('');
                tableBody.append(newRow);

                addSelect2(select2);
                getPrice(select2);
                maskCurrency(newRow.find('[name$="[selling_price]"]'));
            } else if (icon.hasClass('fa-minus')) {
                let currentRow = $(this).parents('tr');
                currentRow.remove();
                updateNumberOfRow();
            }
        });

        //Click cancel button
        $('button.cancel').on('click', function (e) {
            e.preventDefault();
        })
    </script>
@stop