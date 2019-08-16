<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ShapeNoteDetail extends Model
{
    protected $fillable = [
        'shape_note_id',
        'contract_detail_id',
        'manufacturer_note_detail_id',
        'product_id',
        'quantity',
        'status',
        'note'
    ];

    public $timestamps = true;

    protected $attributes = [
        'status' => 10,
    ];

    public function shapeNote()
    {
        return $this->belongsTo('App\ShapeNote');
    }

    public function product()
    {
        return $this->belongsTo('App\Product');
    }

    public function contractDetail()
    {
        return $this->belongsTo('App\ContractDetail');
    }

    public function manufacturerNoteDetail()
    {
        return $this->belongsTo('App\ManufacturerNoteDetail');
    }
}
