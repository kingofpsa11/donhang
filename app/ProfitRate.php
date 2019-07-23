<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\ProfitRate
 *
 * @property int $id
 * @property int $customer_id
 * @property int $category_id
 * @property float $rate
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Category $category
 * @property-read \App\Customer $customer
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ProfitRate newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ProfitRate newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ProfitRate query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ProfitRate whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ProfitRate whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ProfitRate whereCustomerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ProfitRate whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ProfitRate whereRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ProfitRate whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ProfitRate extends Model
{
    protected $fillable = ['customer_id', 'category_id', 'rate'];

    public $timestamps = true;

    public function customer()
    {
        return $this->belongsTo('App\Customer');
    }

    public function category()
    {
        return $this->belongsTo('App\Category');
    }

    public function getRateAttribute($value)
    {
        return str_replace('.',',', number_format($value, 3));
    }

    public function setRateAttribute($value)
    {
        $this->attributes['rate'] = str_replace(',', '.', $value);
    }
}
