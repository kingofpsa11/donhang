<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Supplier
 *
 * @property int $id
 * @property string $name
 * @property string $short_name
 * @property string $address
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $code
 * @property string|null $tax_registration_number
 * @property int|null $status
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\GoodReceive[] $goodReceives
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\ManufacturerOrder[] $manufacturerOrders
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Supplier newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Supplier newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Supplier query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Supplier whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Supplier whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Supplier whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Supplier whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Supplier whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Supplier whereShortName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Supplier whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Supplier whereTaxRegistrationNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Supplier whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Supplier extends Model
{
    protected $fillable = ['id', 'name', 'shortname', 'address'];

    public $timestamps = true;

    public function manufacturerOrders()
    {
        return $this->hasMany('App\ManufacturerOrder');
    }

    public function goodReceives()
    {
        return $this->hasMany('App\GoodReceive');
    }
}
