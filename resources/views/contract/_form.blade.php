@extends('layouts.dashboard')

@section('title', 'Đơn hàng')

@section('action', 'Tạo đơn hàng')

@section('content')

    <!-- Main content -->
    <section class="content container-fluid">
        <form action="@yield('route')" method="POST" id="form">
            @csrf
            @yield('method')
            <div class="box">
                <div class="box-header">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Khách hàng</label>
                                <select class="form-control select2 customer" name="contract[customer_id]" required>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Số đơn hàng</label>
                                <input type="text" class="form-control" placeholder="Nhập số đơn hàng ..." name="contract[number]" value="{{ $contract->number ?? ''}}" required>
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
                                    <input type="text" class="form-control" value="@yield('contract-date')" name="contract[date]" required>
                                </div>
                                <!-- /.input group -->
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Giá trị đơn hàng</label>
                                <input type="text" class="form-control" readonly name="contract[total_value]" value="@yield('contract-total-value')">
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
                            <th class="col-md-1">Mã sản phẩm</th>
                            <th class="col-md-5">Tên sản phẩm</th>
                            <th class="col-md-1">Số lượng</th>
                            <th class="col-md-1">Đơn giá</th>
                            <th class="col-md-2">Tiến độ</th>
                            <th class="col-md-2">Ghi chú</th>
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
                        <a href="{{ route('contract.create') }}" class="btn btn-danger cancel">Hủy</a>
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

            $('tbody').on('change', '[name$="[quantity]"]', calculateTotal);

            function calculateTotal() {
                let rows = $('tr[data-key]');
                let total_value = 0;
                rows.each(function (i, el) {
                    let selling_price = $(el).find('[name$="[selling_price]"]').val().replace(/(\d+).(?=\d{3}(\D|$))/g, "$1");
                    let quantity = $(el).find('[name$="[quantity]"]').val();
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

            function addSelect2 (el) {
                el.select2({
                    placeholder: 'Nhập tên sản phẩm',
                    minimumInputLength: 2,
                    ajax: {
                        url: '{{ route('prices.shows') }}',
                        delay: 200,
                        data: function (params) {
                            let query = {
                                search: params.term,
                                customer_id: customerSelect.val()
                            };

                            return query;
                        },
                        dataType: 'json',
                        dropdownAutoWidth : true,
                        processResults: function (data) {
                            return {
                                results: $.map(data, function (item) {
                                    return {
                                        text: item.name,
                                        id: item.id,
                                        selling_price: item.sellPrice,
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
                    $(this).parents('tr').find('input[name$="[code]"]').val(data.code);
                    $(this).parents('tr').find('input[name$="[selling_price]"]').val(data.selling_price);
                    $(this).parents('tr').find('input[name$="[price_id]"]').val(data.id);
                    calculateTotal();
                });
            }

            let priceSelect = $('.select2.price');

            customerSelect.on('select2:select', function () {
                $('.addRow').removeClass('disabled');
                addSelect2(priceSelect);
                getPrice(priceSelect);
            });

            function updateNumberOfRow() {
                let rows = $('tr[data-key]');
                rows.each(function (i, row) {
                    $(row).attr('data-key', i);
                    $(row).children('[data-col-seq="0"]').text(i + 1);
                    $(row).children('[data-col-seq="1"]').find('input').attr('name', 'contract_detail[' + (i) + '][code]');
                    $(row).children('[data-col-seq="2"]').find('select').attr('name', 'contract_detail[' + (i) + '][price_id]');
                    $(row).children('[data-col-seq="3"]').find('input').attr('name', 'contract_detail[' + (i) + '][quantity]');
                    $(row).children('[data-col-seq="4"]').find('input').attr('name', 'contract_detail[' + (i) + '][selling_price]');
                    $(row).children('[data-col-seq="5"]').find('input').attr('name', 'contract_detail[' + (i) + '][deadline]');
                    $(row).children('[data-col-seq="6"]').find('input').attr('name', 'contract_detail[' + (i) + '][note]');
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
                let select2 = newRow.find('.select2.price');

                newRow.attr('data-key', numberOfProduct);
                newRow.children('[data-col-seq="0"]').text(numberOfProduct + 1);
                newRow.children('[data-col-seq="1"]').find('select').attr('name', 'contract_detail[' + (numberOfProduct) + '][price_id]');
                newRow.children('[data-col-seq="2"]').find('select').attr('name', 'contract_detail[' + (numberOfProduct) + '][price_id]');
                newRow.children('[data-col-seq="3"]').find('input').attr('name', 'contract_detail[' + (numberOfProduct) + '][quantity]');
                newRow.children('[data-col-seq="4"]').find('input').attr('name', 'contract_detail[' + (numberOfProduct) + '][selling_price]');
                newRow.children('[data-col-seq="5"]').find('input').attr('name', 'contract_detail[' + (numberOfProduct) + '][deadline]');
                newRow.children('[data-col-seq="6"]').find('input').attr('name', 'contract_detail[' + (numberOfProduct) + '][note]');
                lastRow.find('button.removeRow').removeClass('hidden');
                newRow.find('button.removeRow').removeClass('hidden');
                newRow.find('.select2-container').remove();
                newRow.find('option').remove();
                newRow.find('input').val('');
                tableBody.append(newRow);

                addSelect2(select2);
                getPrice(select2);
                maskCurrency(newRow.find('[name$="[selling_price]"]'));
                maskDate(newRow.find('[name$="[deadline]"]'));
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

            function convertDateToTimestamp(obj) {
                obj.each(function (i, el) {
                    let date = $(el).val();
                    $(el).inputmask('remove');
                    let datePart = date.split('/');
                    let newDate = new Date(datePart[2], datePart[1] - 1, datePart[0]);
                    $(el).val(newDate.getTime()/1000);
                })
            }

            function convertNumber(obj) {
                obj.each(function (i, el) {
                    $(el).inputmask('remove');
                    $(el).val($(el).val().replace(/(\d+).(?=\d{3})/g, "$1"));
                })
            }

            $('#form').on('submit', function (e) {
                e.preventDefault();
                let form = this;
                let number = $('[name*="number"]').val();
                let customer_id = customerSelect.val();
                let year = $('[name*="date"]').val().split('/')[2];
                $('[name*="number"]').parent().find('span').html('');

                $.get(
                    "{{ route('contract.checkNumber') }}",
                    { number: number, customer_id: customer_id, year: year },
                    function (result) {
                        if (result > 0) {
                            $('[name*="number"]').parent().find('span').html('Đã tồn tại số đơn hàng');
                        } else {
                            convertNumber($('[name$="[selling_price]"]'));
                            convertNumber($('[name$="[total_value]"]'));
                            convertDateToTimestamp($('[name$="[date]"]'));
                            convertDateToTimestamp($('[name$="[deadline]"]'));
                            form.submit();
                        }
                    },
                    "text"
                );
            });
        })
    </script>
@stop