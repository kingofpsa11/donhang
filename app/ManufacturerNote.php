<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class ManufacturerNote extends Model
{
    protected $fillable = ['id', 'number', 'date', 'status'];

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
        $this->attributes['date'] = Carbon::createFromTimestamp($value, 'Asia/Bangkok')->format('Y-m-d');
    }

    public function getDateAttribute($value)
    {
        if (isset($value)) {
            return Carbon::createFromFormat('Y-m-d', $value, 'Asia/Bangkok')->format(config('app.date_format'));
        }

        return $value;
    }
}
