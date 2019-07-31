<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Customer
 *
 * @property int $id
 * @property string|null $code
 * @property string $name
 * @property string $short_name
 * @property string|null $address
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $tax_registration_number
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Contract[] $contracts
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\OutputOrder[] $outputOrders
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Customer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Customer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Customer query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Customer whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Customer whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Customer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Customer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Customer whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Customer whereShortName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Customer whereTaxRegistrationNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Customer whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Customer extends Model
{
    protected $fillable = ['id', 'code', 'name', 'short_name', 'address', 'tax_registration_number'];

    public $timestamps = true;

    public function contracts()
    {
        return $this->hasMany('App\Contract');
    }

    public function outputOrders()
    {
        return $this->hasMany('App\OutputOrder');
    }
}
