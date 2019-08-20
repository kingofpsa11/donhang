<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

/**
 * App\ManufacturerNote
 *
 * @property int $id
 * @property int $number
 * @property string $date
 * @property int $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\ManufacturerNoteDetail[] $manufacturerNoteDetails
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ManufacturerNote newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ManufacturerNote newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ManufacturerNote query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ManufacturerNote whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ManufacturerNote whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ManufacturerNote whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ManufacturerNote whereNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ManufacturerNote whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ManufacturerNote whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ManufacturerNote extends Model
{
    protected $fillable = ['number', 'date', 'status'];

    public $timestamps = true;

    public function manufacturerNoteDetails()
    {
        return $this->hasMany('App\ManufacturerNoteDetail');
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

    public static function getNewNumber()
    {
        return self::whereYear('date',date('Y'))->max('number') + 1 ?? 1;
    }
}
