@extends('layouts.dashboard')

@section('title', 'Đơn hàng')


@section('content')

    <!-- Main content -->
    <div class="row">
        <h3 class="text-right visible-print-block col-xs-6">ĐƠN HÀNG</h3>
        <h4 class="text-center visible-print-block col-xs-6">Số ..../DH2019/</h4>
    </div>
    <h5 class="text-center visible-print-block">Đơn hàng này là PLHĐ & là một phần không thể tách rời của HĐ số: 02/HĐ2016/Hapulico (Hợp đồng mua bán hàng hóa giữa Công ty TNHH MTV Chiếu sáng & thiết bị đô thị  và Công ty CP chiếu sáng Nam Hapulico, đã được ký ngày 04 tháng 01 năm 2016)</h5>
    <div class="visible-print-block">
        <span>Hôm nay, ngày</span><span></span><span>tháng</span><span></span><span>năm</span>
    </div>
    <p class="visible-print-block" style="width: 100%">Tại trụ sở Công ty TNHH MTV Chiếu sáng & thiết bị đô thị, đại diện được ủy quyền của hai bên đồng ý ký nội dung đơn hàng như sau </p>
    <p class="visible-print-block">1. Bên mua đồng ý mua và bên bán đồng ý bán các loại hàng hóa có nội dung theo bảng kê dưới đây (gọi tắt là đơn hàng)</p>

    <div class="box">
        <div class="box-header">
            <div class="hidden-print">
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
            </div>
            <div>

            </div>
            <!-- /.row -->
        </div>
        <!-- /.box-header -->
        <div class="box-body table-responsive no-padding">
            <table class="table table-bordered table-striped hover" id="contract-show">
                <thead>
                    <tr>
                        <th>STT</th>
                        <th class="col-md-1 hidden-print">Mã sản phẩm</th>
                        <th class="col-md-4">Tên sản phẩm</th>
                        <th>ĐV</th>
                        <th class="col-md-1">Số lượng</th>
                        <th class="col-md-1">Đơn giá</th>
                        <th class="col-md-1">Tiến độ</th>
                        <th class="col-md-1 hidden-print">Trạng thái</th>
                        <th class="col-md-1 hidden-print">ĐVSX</th>
                        <th class="col-md-2">Ghi chú</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($contract->contractDetails as $contractDetails)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td class="hidden-print">{{ $contractDetails->price->product->code}}</td>
                            <td>{{ $contractDetails->price->product->name }}</td>
                            <td>{{ $contractDetails->price->product->unit }}</td>
                            <td>{{ $contractDetails->quantity }}</td>
                            <td>{{ $contractDetails->selling_price }}</td>
                            <td>{{ $contractDetails->deadline }}</td>
                            <td class="hidden-print">{!! $contractDetails->status === 10 ? '<span class="label label-primary">Chờ phê duyệt</span>' : ''!!}</td>
                            <td class="hidden-print">{{ $contractDetails->supplier->name }}</td>
                            <td>{{ $contractDetails->note }}</td>
                        </tr>
                    @endforeach
                    <tr class="visible-print">
                        <td colspan="4" class="text-right">Giá trị hợp đồng trước thuế</td>
                        <td class=""></td>
                        <td class=""></td>
                        <td></td>
                    </tr>
                    <tr class="visible-print">
                        <td colspan="4" class="text-right">Thuế giá trị gia tăng VAT 10%</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr class="visible-print">
                        <td colspan="4" class="text-right">Giá trị hợp đồng sau thuế</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr class="visible-print">
                        <td colspan="7">Bằng chữ: </td>
                    </tr>
                </tbody>
            </table>
            <div class="visible-print-block">
                <p>2. Bên mua đã tạm ứng và sẽ thanh toán cho bên bán theo qui định của hợp đồng.</p>
                <p>3. Bên bán cam kết đảm bảo tiến độ cấp hàng theo: tiến độ (quy định tại mục 1 của Đơn hàng này)</p>
                <p>4. Các tài liệu khác kèm theo đơn hàng (nếu có)</p>
                <p>5. Người được bên mua ủy quyền theo dõi, kiểm tra, nhận hàng :  </p>
                <p>6. Phụ lục được lập thành 04 bản có giá trị như nhau, mỗi bên giữ 02 bản. Phụ lục có hiệu lực 30 ngày kể từ ngày ký.</p>
                <div class="col-xs-6 text-center"><strong>ĐẠI DIỆN BÊN BÁN</strong></div>
                <div class="col-xs-6 text-center"><strong>ĐẠI DIỆN BÊN MUA</strong></div>
            </div>
        </div>
        <!-- /.box-body -->
        <div class="box-footer">
            <div class="row hidden-print">
                <div class="col-md-12 text-right">
                    @role(3)
                    <button class="btn btn-primary" id="export">Xuất Excel</button>
                    <a href="{{ route('contracts.edit', $contract)}}" class="btn btn-info">
                        <i class="fa fa-pencil-square-o" aria-hidden="true"></i> Sửa
                    </a>
                    <button class="btn btn-danger" id="delete" data-toggle="modal" data-target="#modal">Xóa</button>
                    @endrole

                    @role(6)
                        @if($contract->status === 10)
                            <form action="{{ route('contracts.update', $contract) }}" method="POST" style="display: inline-block;">
                                @csrf
                                @method('PATCH')
                                <input type="submit" value="Phê duyệt" class="btn btn-success" name="approved">
                            </form>
                        @endif
                    @endrole
                </div>
            </div>
        </div>
    </div>
    <!-- /.box -->
    <form action="{{ route('contracts.destroy', $contract) }}" method="POST">
        @csrf()
        @method('DELETE')
        <div id="modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h4 class="modal-title" id="custom-width-modalLabel">Xóa đơn hàng</h4>
                    </div>
                    <div class="modal-body">
                        <h5>Chắc chắn xóa đơn hàng {{ $contract->number }}?</h5>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default waves-effect remove-data-from-delete-form" data-dismiss="modal">Hủy</button>
                        <input type="submit" class="btn btn-danger waves-effect waves-light" value="Xóa">
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@section('javascript')
    <script src="//cdn.rawgit.com/rainabba/jquery-table2excel/1.1.0/dist/jquery.table2excel.min.js"></script>
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
                        targets     : 4,
                        className   : 'dt-body-center',
                    },
                    {
                        targets: 5,
                        render: $.fn.dataTable.render.number('.', ','),
                        className   : 'dt-body-right'
                    },
                    {
                        targets: 6,
                        className   : 'dt-body-right'
                    },
                    {
                        targets: '_all',
                        className   : 'dt-head-center'
                    }
                ]
            });
            
            $('#export').on('click', function () {
                $('#contract-show').table2excel();
            });

        });
    </script>
@stop