@extends('layouts.dashboard')

@section('content')
    <div class="box">
        <div class="box-header">
            <h3 class="box-title">Bảng tỉ lệ điều chỉnh giá</h3>
            <a href="{{ route('profit-rate.create') }}" class="btn btn-primary pull-right">Tạo mới</a>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
            <table id="profit-rate-table" class="table table-bordered table-striped compact hover row-border" style="width:100%">
                <thead>
                <tr>
                    <th class="text-center">Đơn vị</th>
                    <th class="text-center">Nhóm</th>
                    <th class="text-center">Tỉ lệ</th>
                    <th class="text-center">Action</th>
                </tr>

                </thead>
                <tbody>
                @foreach ($profitRates as $profitRate)
                    <tr>
                        <td>{{ $profitRate->customer->name }}</td>
                        <td>{{ $profitRate->category->name }}</td>
                        <td>{{ $profitRate->rate }}</td>
                        <td>
                            <a href="{{ route('profit-rate.edit', [$profitRate]) }}" class="btn btn-primary">Sửa</a>
                        </td>
                    </tr>
                @endforeach

                </tbody>
                <tfoot>

                </tfoot>
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