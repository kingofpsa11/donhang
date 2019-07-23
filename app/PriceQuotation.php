<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\PriceQuotation
 *
 * @property int $id
 * @property int $customer_id
 * @property int $number
 * @property string $number_of_customer
 * @property string $date
 * @property string $file
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PriceQuotation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PriceQuotation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PriceQuotation query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PriceQuotation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PriceQuotation whereCustomerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PriceQuotation whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PriceQuotation whereFile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PriceQuotation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PriceQuotation whereNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PriceQuotation whereNumberOfCustomer($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PriceQuotation whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class PriceQuotation extends Model
{
    //
}
