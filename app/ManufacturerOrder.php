<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

/**
 * App\ManufacturerOrder
 *
 * @property int $id
 * @property int $contract_id
 * @property int $supplier_id
 * @property string $number
 * @property int $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Contract $contract
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\ManufacturerOrderDetail[] $manufacturerOrderDetails
 * @property-read \App\Supplier $supplier
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ManufacturerOrder newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ManufacturerOrder newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ManufacturerOrder query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ManufacturerOrder whereContractId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ManufacturerOrder whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ManufacturerOrder whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ManufacturerOrder whereNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ManufacturerOrder whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ManufacturerOrder whereSupplierId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ManufacturerOrder whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ManufacturerOrder extends Model
{
    protected $fillable = ['id', 'contract_id', 'supplier_id', 'number', 'status', 'date'];

    public $timestamps = true;

    public function contract()
    {
        return $this->belongsTo('App\Contract');
    }

    public function supplier()
    {
        return $this->belongsTo('App\Supplier');
    }

    public function manufacturerOrderDetails()
    {
        return $this->hasMany('App\ManufacturerOrderDetail');
    }

    protected $attributes = [
        'status' => 10,
    ];

    public function setDateAttribute($value)
    {
        $this->attributes['date'] = Carbon::createFromFormat(config('app.date_format'), $value, 'Asia/Bangkok')->format('Y-m-d');
    }

    public function getDateAttribute($value)
    {
        if (isset($value)) {
            return Carbon::createFromFormat('Y-m-d', $value, 'Asia/Bangkok')->format(config('app.date_format'));
        }

        return $value;
    }

    public static function getNewNumber($supplier_id)
    {
        return (self::whereYear('date', date('Y'))
                    ->where('supplier_id', $supplier_id)
                    ->max('number') + 1) ?? 1;
    }
}
