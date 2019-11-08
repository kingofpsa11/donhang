@extends('layouts.dashboard')

@section('title', 'Bảng chi phí cột thép')

@section('content')
    
    <!-- Main content -->
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <form action="@yield('route')" method="POST" id="form" enctype="multipart/form-data" class="form-horizontal">
                    @csrf
                    @yield('method')
                    <div class="box box-primary">
                        <div class="box-header">
                            <h3 class="box-title">Tạo mới</h3>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <div class="form-group">
                                <label for="" class="control-label col-sm-3">Chủng loại</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control calculate calculate" name="name" value="" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="" class="control-label col-sm-3">Giá thép</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control calculate price" name="gia_thep" id="gia_thep" value="" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="" class="control-label col-sm-3">Dự phòng vật tư</label>
                                <div class="col-sm-9">
                                    <div class="input-group">
                                        <input type="text" class="form-control calculate number" name="du_phong_vat_tu" id="du_phong_vat_tu" value="" required>
                                        <span class="input-group-addon">
                                            <i class="fa fa-percent"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="" class="control-label col-sm-3">Chi phí vật tư phụ</label>
                                <div class="col-sm-9">
                                    <div class="input-group">
                                        <input type="text" class="form-control calculate number" name="vat_tu_phu" id="vat_tu_phu" value="" required>
                                        <span class="input-group-addon">
                                            <i class="fa fa-percent"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="" class="control-label col-sm-3">Hao phí cột</label>
                                <div class="col-sm-9">
                                    <div class="input-group">
                                        <input type="text" class="form-control calculate number" name="hao_phi" id="hao_phi" value="" required>
                                        <span class="input-group-addon">
                                            <i class="fa fa-percent"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="" class="control-label col-sm-3">Chi phí nhân công trực tiếp</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control calculate price" name="nhan_cong_truc_tiep" id="nhan_cong_truc_tiep" value="" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="" class="control-label col-sm-3">Chi phí chung</label>
                                <div class="col-sm-9">
                                    <div class="input-group">
                                        <input type="text" class="form-control calculate number" name="chi_phi_chung" id="chi_phi_chung" value="" required>
                                        <span class="input-group-addon">
                                            <i class="fa fa-percent"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="" class="control-label col-sm-3">Chi phí mạ kẽm</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control calculate price" name="chi_phi_ma_kem" id="chi_phi_ma_kem" value="" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="" class="control-label col-sm-3">Chi phí vận chuyển</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control calculate price" name="chi_phi_van_chuyen" id="chi_phi_van_chuyen" value="" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="" class="control-label col-sm-3">Lãi</label>
                                <div class="col-sm-9">
                                    <div class="input-group">
                                        <input type="text" class="form-control calculate number" name="lai" id="lai" value="" required>
                                        <span class="input-group-addon">
                                            <i class="fa fa-percent"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="" class="control-label col-sm-3">Đơn giá</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control calculate price" name="don_gia" id="don_gia" value="" readonly>
                                </div>
                            </div>
                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer text-right">
                            <input type="submit" class="btn btn-success btn" id="save" value="Lưu">
                            <input type="reset" value="Hủy" class="btn btn-warning">
                        </div>
                    </div>
                    <!-- /.box -->
                </form>
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

            $('.number').inputmask({
                alias               : 'decimal',
                radixPoint          : ',',
                groupSeparator      : '.',
                digits              : '2',
                removeMaskOnSubmit  : true,
                unmaskAsNumber      : true,
            });

            $('.calculate').on('keypress', function () {
                let gia_thep = Number($('#gia_thep').inputmask('unmaskedvalue'));
                let du_phong_vat_tu = Number($('#du_phong_vat_tu').inputmask('unmaskedvalue'));
                let vat_tu_phu = Number($('#vat_tu_phu').inputmask('unmaskedvalue'));
                let hao_phi = Number($('#hao_phi').inputmask('unmaskedvalue'));
                let nhan_cong_truc_tiep = Number($('#nhan_cong_truc_tiep').inputmask('unmaskedvalue'));
                let chi_phi_chung = Number($('#chi_phi_chung').inputmask('unmaskedvalue'));
                let chi_phi_ma_kem = Number($('#chi_phi_ma_kem').inputmask('unmaskedvalue'));
                let chi_phi_van_chuyen = Number($('#chi_phi_van_chuyen').inputmask('unmaskedvalue'));
                let lai = Number($('#lai').inputmask('unmaskedvalue'));

                let don_gia = Math.round(((gia_thep * (1 + (du_phong_vat_tu + vat_tu_phu + hao_phi)/100) + nhan_cong_truc_tiep * 1.3) * (1 + chi_phi_chung/100) + chi_phi_ma_kem + chi_phi_van_chuyen) * (1 + lai/100)/100) * 100;
                $('#don_gia').val(don_gia);
            });

        });
    </script>
@stop