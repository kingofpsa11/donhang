<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

/**
 * App\ContractDetail
 *
 * @property int $id
 * @property int $contract_id
 * @property int $price_id
 * @property int $quantity
 * @property int|null $selling_price
 * @property int $supplier_id
 * @property string $deadline
 * @property string|null $note
 * @property int $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Contract $contract
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\ManufacturerNoteDetail[] $manufacturerNoteDetails
 * @property-read \App\ManufacturerOrderDetail $manufacturerOrderDetail
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\OutputOrderDetail[] $outputOrderDetails
 * @property-read \App\Price $price
 * @property-read \App\Supplier $supplier
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ContractDetail newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ContractDetail newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ContractDetail query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ContractDetail whereContractId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ContractDetail whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ContractDetail whereDeadline($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ContractDetail whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ContractDetail whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ContractDetail wherePriceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ContractDetail whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ContractDetail whereSellingPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ContractDetail whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ContractDetail whereSupplierId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ContractDetail whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ContractDetail extends Model
{
    protected $fillable = ['id', 'contract_id', 'price_id', 'quantity', 'selling_price', 'supplier_id', 'deadline', 'note', 'status'];

    public $timestamps = true;

    protected $attributes = [
        'status' => 10
    ];

    public function contract()
    {
        return $this->belongsTo('App\Contract');
    }

    public function price()
    {
        return $this->belongsTo('App\Price');
    }

    public function outputOrderDetails()
    {
        return $this->hasMany('App\OutputOrderDetail');
    }

    public function manufacturerOrderDetail()
    {
        return $this->hasOne('App\ManufacturerOrderDetail');
    }

    public function manufacturerNoteDetails()
    {
        return $this->hasMany('App\ManufacturerNoteDetail');
    }

    public function supplier()
    {
        return $this->belongsTo('App\Supplier');
    }

    public function setDeadlineAttribute($value)
    {
        $this->attributes['deadline'] = Carbon::createFromFormat(config('app.date_format'), $value, 'Asia/Bangkok')->format('Y-m-d');
    }

    public function getDeadlineAttribute($value)
    {
        if (isset($value)) {
            return Carbon::createFromFormat('Y-m-d', $value, 'Asia/Bangkok')->format(config('app.date_format'));
        }

        return $value;
    }

//    public function setSellingPriceAttribute($value)
//    {
//        $this->attributes['selling_price'] = (int)str_replace('.', '', $value);
//    }
//
//    public function getSellingPriceAttribute($value)
//    {
//        return number_format($value, 0, ',', '.');
//    }
}
