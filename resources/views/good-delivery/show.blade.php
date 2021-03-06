@extends('layouts.dashboard')

@section('title', 'Phiếu xuất kho')

@section('content')
    
    <!-- Main content -->
    <section class="content container-fluid">
        <div class="box">
            <div class="box-header">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Đơn vị nhận hàng</label>
                            <input type="text" class="form-control" value="{{ $goodDelivery->customer->name }}" readonly>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Người nhận hàng</label>
                            <input type="text" class="form-control" name="goodDelivery[supplier]" value="{{ $goodDelivery->supplier_user }}" readonly>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Ngày</label>
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input type="text" class="form-control" value="{{ $goodDelivery->date }}" name="goodDelivery[date]" readonly>
                            </div>
                            <!-- /.input group -->
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Số phiếu</label>
                            <input type="text" class="form-control" name="goodDelivery[number]" value="{{ $goodDelivery->number }}" readonly>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body table-responsive no-padding">
                <table class="table table-bordered table-striped hover" id="contract-show">
                    <thead>
                    <tr>
                        <th>STT</th>
                        <th class="col-md-2">Mã sản phẩm</th>
                        <th class="col-md-6">Tên sản phẩm</th>
                        <th class="col-md-1">Đvt</th>
                        <th class="col-md-1">Kho</th>
                        @role(5)
                        <th class="col-md-1">Số lượng</th>
                        <th class="col-md-1">Số lượng thực xuất</th>
                        @else
                        <th class="col-md-2">Số lượng thực xuất</th>
                        @endrole

                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($goodDelivery->goodDeliveryDetails as $goodDeliveryDetail)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $goodDeliveryDetail->product->code }}</td>
                            <td>{{ $goodDeliveryDetail->product->name ?? ''}}</td>
                            <td></td>
                            <td>{{ $goodDeliveryDetail->store->code ?? ''}}</td>
                            @role(5)
                            <td>{{ $goodDeliveryDetail->quantity }}</td>
                            @endrole
                            <td>{{ $goodDeliveryDetail->actual_quantity }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <!-- /.box-body -->
            <div class="box-footer">
                <div class="row">
                    <div class="col-md-12 text-right">

                        <button class="btn btn-primary" id="export">Xuất Excel</button>
                        @if ( $goodDelivery->status !== 5 )
                            <a href="{{ route('good-deliveries.edit', $goodDelivery)}}" class="btn btn-info">
                                <i class="fa fa-pencil-square-o" aria-hidden="true"></i> Sửa
                            </a>
                            <button class="btn btn-danger" id="delete" data-toggle="modal" data-target="#modal">Xóa</button>
                        @endif

                        @if( $goodDelivery->status === 10 )
                            <form action="{{ route('good-deliveries.update', $goodDelivery) }}" method="POST" style="display: inline-block;">
                                @csrf
                                @method('PATCH')
                                <input type="submit" class="btn btn-success" value="Duyệt" name="approved">
                            </form>
                        @endif

                    </div>
                </div>
            </div>
        </div>
        <!-- /.box -->
    </section>
    @include('shared._modal', [
        'model' => $goodDelivery,
        'modelName' => 'phiếu xuất kho',
        'modelInformation' => $goodDelivery->number,
        'routeName' => 'good-deliveries'
    ])
@endsection

@section('javascript')
    <script src="{{ asset('plugins/input-mask/jquery.inputmask.numeric.extensions.js') }}"></script>
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
                        targets: '_all',
                        className   : 'dt-head-center'
                    }
                ]
            });
            
            $('button.cancel').on('click', function (e) {
                e.preventDefault();
            });
            
            $('#export').on('click', function () {
                $('#contract-show').table2excel();
            });
            
        });
    </script>
@stop