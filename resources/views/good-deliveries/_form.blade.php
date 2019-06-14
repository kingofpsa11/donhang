@extends('layouts.dashboard')

@section('title', 'Phiếu xuất kho')

@section('action', 'Tạo phiếu')

@section('content')
    
    <!-- Main content -->
    <section class="content container-fluid">
        @php
            if(!isset($view)) $view = 'required';
        @endphp
        <form action="@yield('route')" method="POST" id="form">
            @csrf
            @yield('method')
            <div class="box">
                <div class="box-header">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Đơn vị nhận hàng</label>
                                    <select class="form-control" name="goodDelivery[customer_id]" @if($view === 'readonly') disabled @endif style="width:100%;">
                                        @if (isset($goodDelivery))
                                            <option value="{{ $goodDelivery->customer_id }}">{{ $goodDelivery->customer->name }}</option>
                                        @endif
                                    </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Người nhận</label>
                                <input type="text" class="form-control" name="goodDelivery[customer_user]" value="{{ $goodDelivery->customer_user ?? '' }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Ngày</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input type="text" class="form-control" value="{{ $goodDelivery->date ?? date('d/m/Y') }}" name="goodDelivery[date]" {{ $view }}>
                                </div>
                                <!-- /.input group -->
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Số phiếu</label>
                                <input type="text" class="form-control" name="goodDelivery[number]" value="{{ $goodDelivery->number ?? '' }}" {{ $view }}>
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
                            <th class="col-md-7">Tên sản phẩm</th>
                            <th class="col-md-1">Đvt</th>
                            <th class="col-md-1">Kho</th>
                            @if (Request::is('*/edit'))
                                <th class="col-md-1">Số lượng</th>
                                <th class="col-md-1">Số lượng thực xuất</th>
                            @else
                                <th class="col-md-2">Số lượng</th>
                            @endif
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
                        <button class="btn btn-info addRow">Thêm dòng</button>
                        <input type="submit" value="Lưu" class="btn btn-success" name="saveDraft">
                        <input type="reset" value="Hủy" class="btn btn-danger">
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
            
            function maskDate(obj) {
                obj.inputmask({
                    'alias': 'dd/mm/yyyy'
                });
            }
            
            let date = $('[name*="[date]"]');
            
            maskDate(date);
            
            function addCustomerSelect2 (el) {
                el.select2({
                    placeholder: 'Nhập đơn vị nhận hàng',
                    minimumInputLength: 2,
                    ajax: {
                        url: '{{ route('customer.listCustomer') }}',
                        delay: 200,
                        dataType: 'json',
                        dropdownAutoWidth : true,
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
            }
            
            function addProductSelect2 (el) {
                el.select2({
                    width: 'style',
                    placeholder: 'Nhập tên sản phẩm',
                    minimumInputLength: 2,
                    ajax: {
                        url: '{{ route('product.getProduct') }}',
                        delay: 200,
                        dataType: 'json',
                        dropdownAutoWidth : true,
                        processResults: function (data) {
                            return {
                                results: $.map(data, function (item) {
                                    return {
                                        text: item.name,
                                        id: item.id,
                                        product_id: item.product_id,
                                        code: item.code
                                    }
                                })
                            };
                        },
                        cache: true
                    },
                });
                
                el.on('select2:select', function (e) {
                    let data = e.params.data;
                    let row = el.closest('tr');
                    row.find('input[name*="code"]').val(data.code);
                });
            }
            
            let productSelect = $('.product_id');
            addProductSelect2(productSelect);
            
            let customerInput = $('[name*="customer_id"]');
            addCustomerSelect2(customerInput);
            
            function updateNumberOfRow() {
                let rows = $('tr[data-key]');
                rows.each(function (i, row) {
                    $(row).attr('data-key', i);
                    $(row).children('[data-col-seq="0"]').find('span', i + 1);
                    $(row).children('[data-col-seq="1"]').find('input').attr('name', 'goodDeliveryDetails[' + i + '][code]');
                    $(row).children('[data-col-seq="2"]').find('select').attr('name', 'goodDeliveryDetails[' + i + '][product_id]');
                    $(row).children('[data-col-seq="3"]').find('input').attr('name', 'goodDeliveryDetails[' + i + '][unit]');
                    $(row).children('[data-col-seq="4"]').find('input').attr('name', 'goodDeliveryDetails[' + i + '][store_id]');
                    $(row).children('[data-col-seq="5"]').find('input').attr('name', 'goodDeliveryDetails[' + i + '][quantity]');
                    
                    if (rows.length === 1) {
                        $(row).find('button.removeRow').addClass('hidden');
                    } else {
                        $(row).find('button.removeRow').removeClass('hidden');
                    }
                });
            }
            
            updateNumberOfRow();
            
            //Add or remove row to table
            $('.box-footer').on('click', '.addRow:not(".disabled")',function (e) {
                e.preventDefault();
                let tableBody = $('tbody');
                let numberOfProduct = tableBody.children().length;
                let lastRow = $('tr:last');
                let newRow = lastRow.clone();
                let select2 = newRow.find('.product_id');
                
                newRow.attr('data-key', numberOfProduct);
                newRow.children('[data-col-seq="0"]').text(numberOfProduct + 1);
                newRow.children('[data-col-seq="2"]').find('select').attr('name', 'goodDeliveryDetails[' + (numberOfProduct) + '][product_id]');
                newRow.children('[data-col-seq="3"]').find('input').attr('name', 'goodDeliveryDetails[' + (numberOfProduct) + '][unit]');
                newRow.children('[data-col-seq="4"]').find('input').attr('name', 'goodDeliveryDetails[' + (numberOfProduct) + '][store_id]');
                newRow.children('[data-col-seq="5"]').find('input').attr('name', 'goodDeliveryDetails[' + (numberOfProduct) + '][quantity]');
                lastRow.find('button.removeRow').removeClass('hidden');
                newRow.find('button.removeRow').removeClass('hidden');
                newRow.find('.select2-container').remove();
                newRow.find('option').remove();
                newRow.find('input').val('');
                tableBody.append(newRow);
                
                addProductSelect2(select2);
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
                });
            }
            
            $('#form').on('submit', function () {
                convertDateToTimestamp($('[name$="[date]"]'));
            });
        })
    </script>
@stop