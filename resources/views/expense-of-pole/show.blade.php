@extends('layouts.dashboard')

@section('title', 'Chi phí')


@section('content')
    <!-- Main content -->
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Bảng chi phí cột thép</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body table-responsive">
                        <table id="example2" class="table table-bordered table-striped compact hover row-border" style="width:100%">
                            <thead>
                                <tr>
                                    <th class="text-center">Chi phí</th>
                                    <th class="text-center">Đơn giá/Tỷ lệ</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th>Chủng loại</th>
                                    <td>{{ $expenseOfPole->name }}</td>
                                </tr>
                                <tr>
                                    <th>Giá thép</th>
                                    <td class="price">{{ $expenseOfPole->gia_thep }}</td>
                                </tr>
                                <tr>
                                    <th>Dự phòng vật tư</th>
                                    <td class="text-right">{{ $expenseOfPole->du_phong_vat_tu }} %</td>
                                </tr>
                                <tr>
                                    <th>Chi phí vật tư phụ</th>
                                    <td class="text-right">{{ $expenseOfPole->vat_tu_phu }} %</td>
                                </tr>
                                <tr>
                                    <th>Hao phí cột</th>
                                    <td class="text-right">{{ $expenseOfPole->hao_phi }} %</td>
                                </tr>
                                <tr>
                                    <th>Chi phí nhân công trực tiếp</th>
                                    <td class="price">{{ $expenseOfPole->nhan_cong_truc_tiep }}</td>
                                </tr>
                                <tr>
                                    <th>Chi phí chung</th>
                                    <td class="text-right">{{ $expenseOfPole->chi_phi_chung }} %</td>
                                </tr>
                                <tr>
                                    <th>Chi phí mạ kẽm</th>
                                    <td class="price">{{ $expenseOfPole->chi_phi_ma_kem }}</td>
                                </tr>
                                <tr>
                                    <th>Chi phí vận chuyển</th>
                                    <td class="price">{{ $expenseOfPole->chi_phi_van_chuyen }}</td>
                                </tr>
                                <tr>
                                    <th>Lãi</th>
                                    <td class="text-right">{{ $expenseOfPole->lai }} %</td>
                                </tr>
                                <tr>
                                    <th>Đơn giá</th>
                                    <td class="price">{{ $expenseOfPole->don_gia }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer text-right">
                        <a href="{{ route('expense-of-pole.create') }}" class="btn btn-success">Tạo mới</a>
                        <a href="{{ route('expense-of-pole.edit', $expenseOfPole) }}" class="btn btn-primary">Sửa</a>
                    </div>
                </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>
        </div>
    </section>
@endsection

@section('javascript')
    <script>
        $(function () {
            $(".price").inputmask("integer", {
                groupSeparator  : '.',
                autoGroup       : true,
                removeMaskOnSubmit  : true
            });
        });
    </script>
@stop