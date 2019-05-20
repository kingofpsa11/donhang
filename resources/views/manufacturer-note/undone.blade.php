@extends('layouts.dashboard')

@section('content')
    <div class="box">
        <div class="box-header">
            <h3 class="box-title">Tổng hợp LXH</h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body table-responsive">
            <table class="table table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th class="col-md-1">STT</th>
                        <th class="col-md-5">ĐVXH</th>
                        <th class="col-md-2">Ngày xuất</th>
                        <th class="col-md-2">Số LXH</th>
                        <th class="col-md-1">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($undoneOutputOrders as $outputOrder)
                        <tr>
                            <td class="col-md-1">{{ $loop->iteration }}</td>
                            <td class="col-md-5">{{ $outputOrder->customer->name }}</td>
                            <td class="col-md-2">{{ $outputOrder->date }}</td>
                            <td class="col-md-2">{{ $outputOrder->number }}</td>
                            <td class="col-md-1">
                                <a href="{{ route('good-delivery.create', [$outputOrder])}}" class="btn btn-success">
                                    <i class="fa fa-pencil-square-o" aria-hidden="true"></i> Phiếu xuất
                                </a>
                            </td>
                        </tr>
                    @endforeach
                    
                </tbody>
            </table>
        </div>
        <!-- /.box-body -->
    </div>
    <!-- /.box -->
@stop

@section('javascript')
    <script>
    </script>
@stop