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

    public function product()
    {
        return $this->belongsTo('App\Product');
    }

    public function deliveries()
    {
        return $this->morphMany('App\GoodDeliveryDetail', 'deliverable');
    }

    public function receive()
    {
        return $this->morphOne('App\GoodReceiveDetail', 'receivable');
    }

}
