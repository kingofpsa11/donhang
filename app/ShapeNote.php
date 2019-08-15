<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class ShapeNote extends Model
{
    protected $fillable = ['date', 'number' ,'status'];

    public $timestamps = true;

    protected $attributes = [
        'status' => 10
    ];

    protected $casts = [
        'date' => 'date: Y-m-d'
    ];

    public function shapeNoteDetails()
    {
        return $this->hasMany('App\ShapeNoteDetail');
    }

    public static function getNewNumber()
    {
        return self::whereYear('date', date('Y'))->max('number') + 1 ?? 1;
    }

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
}
