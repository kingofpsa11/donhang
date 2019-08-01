<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Store
 *
 * @property int $id
 * @property string $code
 * @property string $name
 * @property string $category
 * @property int $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Store newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Store newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Store query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Store whereCategory($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Store whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Store whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Store whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Store whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Store whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Store whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Store extends Model
{
    protected $fillable = ['id', 'code', 'category', 'store_id', 'status'];

    public $timestamps = true;

    protected $attributes = [
        'status' => 1
    ];

    public function childrenStore()
    {
        return $this->hasOne('App\Store','id', 'store_id');
    }
}
