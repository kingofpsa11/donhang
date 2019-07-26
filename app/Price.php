<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

/**
 * App\Price
 *
 * @property int $id
 * @property int $product_id
 * @property int|null $purchase_price
 * @property int|null $selling_price
 * @property int $status
 * @property string $effective_date
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\ContractDetail[] $contract_details
 * @property-read \App\Product $product
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Price newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Price newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Price query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Price whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Price whereEffectiveDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Price whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Price whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Price wherePurchasePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Price whereSellingPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Price whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Price whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Price extends Model
{
    protected $fillable = ['id', 'product_id', 'purchase_price', 'selling_price', 'status', 'effective_date'];

    public $timestamps = true;

    protected $attributes = [
        'status' => 10
    ];

    public function contractDetails()
    {
        return $this->hasMany('App\ContractDetail');
    }

    public function product()
    {
        return $this->belongsTo('App\Product');
    }

    public function setEffectiveDateAttribute($value)
    {
        $this->attributes['effective_date'] = Carbon::createFromFormat(config('app.date_format'), $value, 'Asia/Bangkok')->format('Y-m-d');
    }

    public function getEffectiveDateAttribute($value)
    {
        if (isset($value)) {
            return Carbon::createFromFormat('Y-m-d', $value, 'Asia/Bangkok')->format(config('app.date_format'));
        }

        return $value;
    }
}
