<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StepNoteDetail extends Model
{
    protected $fillable = [
        'id',
        'step_note_id',
        'contract_detail_id',
        'product_id',
        'quantity',
        'status'
    ];

    public $timestamps = true;

    protected $attributes = [
        'status' => 10,
    ];

    public function stepNote()
    {
        return $this->belongsTo('App\StepNote');
    }

    public function contractDetail()
    {
        return $this->belongsTo('App\ContractDetail');
    }
}
