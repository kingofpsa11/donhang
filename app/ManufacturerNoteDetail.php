<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ManufacturerNoteDetail extends Model
{
    protected $fillable = ['id', 'manufacturer_note_id', 'contract_detail_id', 'bom_id', 'quantity', 'note'];

    public $timestamps = true;

    public function manufacturerNote()
    {
        return $this->belongsTo('App\ManufacturerNote');
    }

    public function contractDetail()
    {
        return $this->belongsTo('App\ContractDetail');
    }

    public function bom()
    {
        return $this->belongsTo('App\Bom');
    }
}