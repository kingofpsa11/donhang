@extends('layouts.dashboard')

@section('title', 'Đơn hàng')

@section('action', 'Tạo đơn hàng')

@section('content')

    <!-- Main content -->
    <form action="@yield('route')" method="POST" id="form">
        @csrf
        @yield('method')
        <div class="box">
            <div class="box-header">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="form-group">
                            <label for="selectCustomer">Khách hàng</label>
                            <select class="form-control select2 customer" name="customer_id" id="selectCustomer" style="width: 100%" required>
                                @if (isset($contract))
                                    <option value="{{ $contract->customer_id }}">{{ $contract->customer->name }}</option>
                                @endif
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Số đơn hàng</label>
                            <input type="text" class="form-control" placeholder="Nhập số đơn hàng ..." name="number" value="{{ $contract->number ?? ''}}" required>
                            <span class="check-number text-red"></span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Ngày đặt hàng</label>
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input type="text" class="form-control" value="{{ $contract->date ?? date('d/m/Y') }}" name="date" required>
                            </div>
                            <!-- /.input group -->
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Giá trị đơn hàng</label>
                            <input type="text" class="form-control currency" readonly name="total_value" value="{{ $contract->total_value ?? ''}}">
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body table-responsive">
                <table id="example1" class="table table-bordered table-striped table-condensed">
                    <thead>
                    <tr>
                        <th>STT</th>
                        <th>Mã SP</th>
                        <th class="col-xs-5">Tên sản phẩm</th>
                        <th class="col-xs-1">Số lượng</th>
                        <th class="col-xs-1">Đơn giá</th>
                        <th class="col-xs-2">Tiến độ</th>
                        <th class="col-xs-1">Đơn vị SX</th>
                        <th class="col-xs-2">Ghi chú</th>
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
                    <button class="btn btn-primary addRow
                        @if (Request::is('*/create'))
                            disabled
                        @endif
                    ">Thêm dòng</button>
                    <input type="submit" value="Lưu" class="btn btn-success save">
                    <a href="{{ route('contracts.index') }}" class="btn btn-danger cancel">Hủy</a>
                </div>
            </div>
        </div>
        <!-- /.box -->
    </form>
@endsection

@section('javascript')
    <script>
        $(document).ready(function () {
            let total_value_obj = $('[name="total_value"]');
            let selling_price = $('[name*="selling_price"]');
            let date = $('[name="date"]');
            let deadline = $('[name*="deadline"]');
            let customerSelect = $('.select2.customer');

            customerSelect.select2({
                placeholder: 'Nhập khách hàng',
                minimumInputLength: 1,
                ajax: {
                    url: '{{ route('customer.listCustomer') }}',
                    delay: 200,
                    dataType: 'json',
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
                },
            });

            $('tbody').on('change', '[name*="quantity"]', calculateTotal);

            function calculateTotal() {
                let rows = $('tr[data-key]');
                let total_value = 0;
                rows.each(function (i, el) {
                    let selling_price = $(el).find('[name*="selling_price"]').val().replace(/(\d+).(?=\d{3}(\D|$))/g, "$1");
                    let quantity = $(el).find('[name*="quantity"]').val();
                    total_value += selling_price * quantity;
                });

                total_value_obj.val(total_value);
            }

            function maskCurrency(obj) {
                obj.inputmask("integer",{
                    groupSeparator  : '.',
                    autoGroup       : true,
                    removeMaskOnSubmit  : true,
                    unmaskAsNumber: true,
                });
            }

            function maskDate(obj) {
                obj.inputmask({
                    'alias': 'dd/mm/yyyy'
                });
            }

            // maskCurrency(total_value);
            maskCurrency($('.currency'));
            maskCurrency(selling_price);
            maskDate(date);
            maskDate(deadline);

            function addSelect2 (el) {
                el.select2({
                    placeholder: 'Nhập tên sản phẩm',
                    minimumInputLength: 2,
                    width: 'resolve',
                    ajax: {
                        url: '{{ route('prices.shows') }}',
                        delay: 200,
                        data: function (params) {
                            return {
                                search: params.term,
                                customer_id: customerSelect.val()
                            };
                        },
                        dataType: 'json',
                        dropdownAutoWidth : true,
                        processResults: function (data) {
                            return {
                                results: $.map(data, function (item) {
                                    return {
                                        text: item.name,
                                        id: item.id,
                                        selling_price: item.sell_price,
                                        code: item.code
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
                    $(this).parents('tr').find('input[name*="code"]').val(data.code);
                    $(this).parents('tr').find('.code').text(data.code);
                    $(this).parents('tr').find('input[name*="selling_price"]').val(data.selling_price);
                    $(this).parents('tr').find('input[name*="price_id"]').val(data.id);
                    calculateTotal();
                });
            }

            let priceSelect = $('.select2.price');

            customerSelect.on('select2:select', function () {
                $('.addRow').removeClass('disabled');
            });

            addSelect2(priceSelect);
            getPrice(priceSelect);

            function updateNumberOfRow() {
                let rows = $('tr[data-key]');
                rows.each(function (i, row) {
                    $(row).attr('data-key', i);
                    $(row).children('[data-col-seq="0"]').find('span').text(i + 1);
                    $(row).find('[name]').each(function (index, el) {
                        let oldName = $(el).attr('name');
                        let pos = oldName.indexOf('[');
                        let newName = oldName.substr(0, pos + 1) + i + oldName.substr(pos + 2);
                        $(el).attr('name', newName);
                    });
                    if (i === 0) {
                        if (rows.length === 1) {
                            $(row).find('button.removeRow').addClass('hidden');
                        } else {
                            $(row).find('button.removeRow').removeClass('hidden');
                        }
                    }
                });
            }

            updateNumberOfRow();

            //Add or remove row to table
            $('.box-footer').on('click', '.addRow:not(".disabled")',function (e) {
                e.preventDefault();
                let tableBody = $('#example1 tbody');
                let numberOfProduct = tableBody.children().length;
                let lastRow = tableBody.find('tr:last');
                let newRow = lastRow.clone();
                let select2 = newRow.find('.select2.price');

                newRow.attr('data-key', numberOfProduct);
                newRow.children('[data-col-seq="0"]').find('span').text(numberOfProduct + 1);
                lastRow.find('button.removeRow').removeClass('hidden');
                newRow.find('button.removeRow').removeClass('hidden');
                newRow.find('.select2-container').remove();
                newRow.children('[data-col-seq="2"]').find('option').remove();
                newRow.find('input').val('');
                tableBody.append(newRow);

                addSelect2(select2);
                getPrice(select2);
                maskCurrency(newRow.find('[name*="selling_price"]'));
                maskDate(newRow.find('[name*="deadline"]'));
                updateNumberOfRow();
            });

            $('#example1').on('click', '.removeRow', function () {
                let currentRow = $(this).parents('tr');
                currentRow.remove();
                updateNumberOfRow();
            });

            let number = $('[name="number"]');

            number.on('focusout', function () {
                let numberObj = $('[name="number"]');
                let number = numberObj.val();
                let customer_id = customerSelect.val();
                let year = $('[name="date"]').val().split('/')[2];
                let submitButton = $('.save');

                if (number !== '') {
                    $.get(
                        "{{ route('contracts.exist_number') }}",
                        {number: number, customer_id: customer_id, year: year},
                        function (result) {
                            if (result > 0 && window.location.pathname.indexOf('create') >= 0) {
                                numberObj.parent().find('span').html('Đã tồn tại số đơn hàng');
                                submitButton.attr('disabled', 'disabled');
                            } else {
                                numberObj.parent().find('span').html('');
                                submitButton.prop('disabled', false);
                            }
                        },
                        "text"
                    );
                }
            });

            number.on('keypress', function () {
                $(this).parent().find('span').html('');
            })
        })
    </script>
@stop