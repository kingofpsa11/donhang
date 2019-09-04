@extends('layouts.dashboard')

@section('title', 'Đơn hàng')


@section('content')
    <!-- Main content -->
    <div class="row">
        <h2 class="text-right visible-print-block col-xs-6">ĐƠN HÀNG</h2>
        <h4 class="text-center visible-print-block col-xs-6">Số ..../DH2019/</h4>
    </div>
    <strong class="text-center visible-print-block">Đơn hàng này là PLHĐ & là một phần không thể tách rời của HĐ số: 02/HĐ2016/Hapulico (Hợp đồng mua bán hàng hóa giữa Công ty TNHH MTV Chiếu sáng & thiết bị đô thị  và Công ty CP chiếu sáng Nam Hapulico, đã được ký ngày 04 tháng 01 năm 2016)</strong>
    <div class="visible-print-block">
        <span>Hôm nay, ngày </span><span>{{ substr($contract->date, 0, 2) }}</span><span> tháng </span><span>{{ substr($contract->date, 3, 2) }}</span><span> năm </span><span>{{ substr($contract->date,6 ) }}</span>
    </div>
    <p class="visible-print-block" style="width: 100%">Tại trụ sở Công ty TNHH MTV Chiếu sáng & thiết bị đô thị, đại diện được ủy quyền của hai bên đồng ý ký nội dung đơn hàng như sau </p>
    <p class="visible-print-block">1. Bên mua đồng ý mua và bên bán đồng ý bán các loại hàng hóa có nội dung theo bảng kê dưới đây (gọi tắt là đơn hàng)</p>

    <div class="box">
        <div class="box-header">
            <div class="hidden-print">
                <h3 class="box-title">Nội dung đơn hàng</h3>
                <div class="row">
                    <div class="col-md-12">
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
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Trạng thái</label>
                            <input type="text" class="form-control"
                                value="@php
                                   switch ($contract->status) {
                                    case 10:
                                        echo 'Đã lập';
                                        break;
                                    case 9:
                                        echo 'Chờ phê duyệt';
                                        break;
                                    case 8:
                                        echo 'Đã phê duyệt';
                                        break;
                                    default:
                                        echo $contract->status;
                                   }
                                @endphp"
                                readonly>
                        </div>
                    </div>
                </div>
            </div>
            <div>

            </div>
            <!-- /.row -->
        </div>
        <!-- /.box-header -->
        <div class="box-body table-responsive">
            <table class="table table-bordered table-striped hover" id="contract-show">
                <thead>
                    <tr>
                        <th>STT</th>
                        <th class="hidden-print">Mã sản phẩm</th>
                        <th class="col-md-5">Tên sản phẩm</th>
                        <th>ĐV</th>
                        <th class="">Số lượng</th>
                        <th class="col-md-1">Đơn giá</th>
                        <th class="col-md-1">Tiến độ</th>
                        <th class="hidden-print">Trạng thái</th>
                        <th class="hidden-print">ĐVSX</th>
                        <th class="hidden-print">Bản vẽ</th>
                        <th class="">Ghi chú</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($contract->contractDetails as $contractDetails)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td class="hidden-print">{{ $contractDetails->price->product->code}}</td>
                            <td style="white-space: nowrap">{{ $contractDetails->price->product->name }}</td>
                            <td>{{ $contractDetails->price->product->unit }}</td>
                            <td>{{ $contractDetails->quantity }}</td>
                            <td>{{ $contractDetails->selling_price }}</td>
                            <td>{{ $contractDetails->deadline }}</td>
                            <td class="hidden-print">{!! $contractDetails->status !!}</td>
                            <td class="hidden-print">{{ $contractDetails->supplier->name }}</td>
                            <td class="hidden-print">
                                @foreach($contractDetails->price->product->images as $image)
                                    {{--<div class="btn btn-default">--}}
                                        <a href="{{ asset('storage/' . $image->link) }}" download>
                                            <span class="glyphicon glyphicon-download"></span> {{ $image->name }}
                                        </a>
                                    {{--</div>--}}
                                @endforeach
                            </td>
                            <td>{{ $contractDetails->note }}</td>
                        </tr>
                    @endforeach
                    <tr class="visible-print">
                        <td colspan="4" class="text-right">Giá trị hợp đồng trước thuế</td>
                        <td class="value text-right">{{ $contract->total_value }}</td>
                        <td class=""></td>
                        <td></td>
                    </tr>
                    <tr class="visible-print">
                        <td colspan="4" class="text-right">Thuế giá trị gia tăng VAT 10%</td>
                        <td class="value text-right">{{ $contract->total_value * 0.1 }}</td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr class="visible-print">
                        <td colspan="4" class="text-right">Giá trị hợp đồng sau thuế</td>
                        <td class="value text-right">{{ $contract->total_value * 1.1 }}</td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr class="visible-print">
                        <td colspan="7">Bằng chữ: {{ $value }}</td>
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
                    @if (auth()->user()->id === $contract->user_id)
                    <a href="{{ route('contracts.create')}}" class="btn btn-success">
                        <i class="fa fa-pencil-square-o" aria-hidden="true"></i> Mới
                    </a>
                    <a href="{{ route('contracts.edit', $contract)}}" class="btn btn-info">
                        <i class="fa fa-pencil-square-o" aria-hidden="true"></i> Sửa
                    </a>
                    <button class="btn btn-danger" id="delete" data-toggle="modal" data-target="#modal">Xóa</button>
                    @endif
                    @endrole

                    @role(6)
                        @if($contract->status === 9)
                            <form action="{{ route('contracts.update', $contract) }}" method="POST" style="display: inline-block;">
                                @csrf
                                @method('PATCH')
                                <input type="submit" value="Phê duyệt" class="btn btn-success" name="approved">
                            </form>
                        @endif
                    @endrole

                    @role(7)
                    @if($contract->status === 10)
                        <form action="{{ route('contracts.update', $contract) }}" method="POST" style="display: inline-block;">
                            @csrf
                            @method('PATCH')
                            <input type="submit" value="Kí nháy" class="btn btn-success" name="signed">
                        </form>
                    @endif
                    @endrole
                </div>
            </div>
        </div>
    </div>
    <!-- /.box -->
    @include('shared._modal', [
       'model' => $contract,
       'modelName' => 'Đơn hàng',
       'modelInformation' => $contract->number . 'của' . $contract->customer->name,
       'routeName' => 'contracts'
   ])
@endsection

@section('javascript')
    <script src="//cdn.rawgit.com/rainabba/jquery-table2excel/1.1.0/dist/jquery.table2excel.min.js"></script>
    <script>
        $(function () {
            $('[data-mask]').inputmask();
            $('.value').inputmask('integer', {
                autoGroup: true,
                groupSeparator: '.'
            });

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