<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ExpenseOfPole extends Model
{
    protected $fillable = [
        'gia_thep',
        'name',
        'du_phong_vat_tu',
        'vat_tu_phu',
        'hao_phi',
        'nhan_cong_truc_tiep',
        'chi_phi_chung',
        'chi_phi_ma_kem',
        'chi_phi_van_chuyen',
        'lai',
        'don_gia'
    ];

    public $timestamps = true;

    public function getRateAttribute($value)
    {
        return str_replace('.',',', number_format($value, 3));
    }
}
