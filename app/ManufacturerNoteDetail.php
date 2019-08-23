<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\ManufacturerNoteDetail
 *
 * @property int $id
 * @property int $manufacturer_note_id
 * @property int $contract_detail_id
 * @property int|null $bom_detail_id
 * @property int|null $product_id
 * @property int $quantity
 * @property string|null $note
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\BomDetail|null $bomDetail
 * @property-read \App\ContractDetail $contractDetail
 * @property-read \App\ManufacturerNote $manufacturerNote
 * @property-read \App\Product|null $product
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ManufacturerNoteDetail newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ManufacturerNoteDetail newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ManufacturerNoteDetail query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ManufacturerNoteDetail whereBomDetailId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ManufacturerNoteDetail whereContractDetailId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ManufacturerNoteDetail whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ManufacturerNoteDetail whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ManufacturerNoteDetail whereManufacturerNoteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ManufacturerNoteDetail whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ManufacturerNoteDetail whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ManufacturerNoteDetail whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ManufacturerNoteDetail whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ManufacturerNoteDetail extends Model
{
    protected $fillable = [
        'id',
        'manufacturer_note_id',
        'contract_detail_id',
        'product_id',
        'bom_detail_id',
        'length',
        'thickness',
        'top_perimeter',
        'bottom_perimeter',
        'quantity',
        'note',
        'status'];

    public $timestamps = true;

    public function manufacturerNote()
    {
        return $this->belongsTo('App\ManufacturerNote');
    }

    public function contractDetail()
    {
        return $this->belongsTo('App\ContractDetail');
    }

    public function bomDetail()
    {
        return $this->belongsTo('App\BomDetail');
    }

    public function product()
    {
        return $this->belongsTo('App\Product');
    }

    public function stepables()
    {
        return $this->morphMany('App\StepNoteDetail', 'stepable');
    }

    protected $attributes = [
        'status' => 10
    ];
}
