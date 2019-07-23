<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\BomDetail
 *
 * @property int $id
 * @property int $bom_id
 * @property int $product_id
 * @property float $quantity
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Bom $bom
 * @property-read \App\Product $product
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BomDetail newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BomDetail newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BomDetail query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BomDetail whereBomId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BomDetail whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BomDetail whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BomDetail whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BomDetail whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BomDetail whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class BomDetail extends Model
{
    protected $fillable = ['id', 'bom_id', 'product_id', 'quantity'];

    public $timestamps = true;

    public function bom()
    {
        return $this->belongsTo('App\Bom');
    }

    public function product()
    {
        return $this->belongsTo('App\Product');
    }
}
