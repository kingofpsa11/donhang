@extends('layouts.dashboard')

@section('title', 'Đơn hàng')


@section('content')

    <!-- Main content -->
    <section class="content container-fluid">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">Nội dung đơn hàng</h3>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Đơn vị đặt hàng</label>
                            <input type="text" class="form-control" readonly value="{{ $contract->customer->name }}">
                        </div>
                    </div>
                    <!-- /.col -->
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Số đơn hàng</label>
                            <input type="text" class="form-control" readonly value="{{ $contract->number }}">
                        </div>
                    </div>
                <!-- /.col -->
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Ngày đặt hàng</label>
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input type="text" class="form-control" name="contract[date]" value="{{ $contract->date }}" readonly>
                            </div>
                            <!-- /.input group -->
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Giá trị đơn hàng</label>
                            <input type="text" class="form-control" value="{{ $contract->total_value }}" readonly data-inputmask="'alias': 'integer', 'autoGroup': true, 'groupSeparator': '.', 'removeMaskOnSubmit': true" data-mask>
                        </div>
                    </div>
                </div>
                <!-- /.row -->
            </div>
            <!-- /.box-header -->
            <div class="box-body table-responsive no-padding">
                <table class="table table-bordered table-striped hover" id="contract-show">
                    <thead>
                        <tr>
                            <th>STT</th>
                            <th class="col-md-5">Tên sản phẩm</th>
                            <th class="col-md-1">Số lượng</th>
                            <th class="col-md-1">Đơn giá</th>
                            <th class="col-md-2">Tiến độ</th>
                            <th>Trạng thái</th>
                            <th class="col-md-2">Ghi chú</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php($i=1)
                        @foreach ($contract->contract_details as $contract_detail)
                            <tr>
                                <td>{{ $i }}</td>
                                <td>{{ $contract_detail->price->product->name }}</td>
                                <td>{{ $contract_detail->quantity }}</td>
                                <td>{{ $contract_detail->selling_price }}</td>
                                <td>{{ $contract_detail->deadline }}</td>
                                <td>{{ $contract_detail->status }}</td>
                                <td>{{ $contract_detail->note }}</td>
                            </tr>
                            @php($i++)
                        @endforeach
                    </tbody>
                </table>
            </div>
            <!-- /.box-body -->
            <div class="box-footer">
                <div class="row">
                    <div class="col-md-3 pull-right">
                        <a href="{{ route('contract.edit', ['contract' => $contract->id])}}" class="btn btn-info pull-right col-md-3">
                            <i class="fa fa-pencil-square-o" aria-hidden="true"></i> Sửa
                        </a>
                        <button class="btn btn-primary pull-right col-md-3" id="export">Export</button>
                        <a href="{{ route('manufacturer-order.create', [$contract]) }}" class="btn btn-warning pull-right col-md-3" id="manufacturer_order">LSX</a>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.box -->
    </section>
@endsection

@section('javascript')
    <script src="{{ asset('plugins/input-mask/jquery.inputmask.numeric.extensions.js') }}"></script>
    <script>
        $(function () {
            $('[data-mask]').inputmask();

            let customerSelect = $('.select2.customer');
            customerSelect.select2();

            $('#contract-show').DataTable({
                'paging'        : false,
                'lengthChange'  : false,
                'info'          : false,
                searching       : false,
                ordering        : false,
                columnDefs: [
                    {
                        targets: [ 3 ],
                        render: $.fn.dataTable.render.number('.', ','),
                        className   : 'dt-body-right'
                    },
                    {
                        targets: [ 4 ],
                        className   : 'dt-body-right'
                    },
                    {
                        targets: '_all',
                        className   : 'dt-head-center'
                    }
                ]
            });

            $('button.cancel').on('click', function (e) {
                e.preventDefault();
            });

            $('#export').on('click', function () {
                let tab_text="<table border='2px'><tr bgcolor='#87AFC6'>";
                let tab = document.getElementsByTagName('table')[0]; // id of table

                for(let j = 0 ; j < tab.rows.length ; j++)
                {
                    tab_text=tab_text+tab.rows[j].innerHTML+"</tr>";
                    //tab_text=tab_text+"</tr>";
                }

                tab_text=tab_text+"</table>";
                tab_text= tab_text.replace(/<A[^>]*>|<\/A>/g, "");//remove if u want links in your table
                tab_text= tab_text.replace(/<img[^>]*>/gi,""); // remove if u want images in your table
                tab_text= tab_text.replace(/<input[^>]*>|<\/input>/gi, ""); // remove input params

                return window.open('data:application/vnd.ms-excel,' + encodeURIComponent(tab_text));
            });
            
            //create manufacturer order
            $('#manufacturer_order').on('click', function () {

            })
        });
    </script>
@stop