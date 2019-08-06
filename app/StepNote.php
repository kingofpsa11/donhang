<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class StepNote extends Model
{
    protected $fillable = [
        'number',
        'date',
        'step_id',
    ];

    public $timestamps = true;

    public function stepNoteDetails()
    {
        return $this->hasMany('App\StepNoteDetail');
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

    public function contractDetail()
    {
        return $this->belongsTo('App\ContractDetail');
    }

    public function step()
    {
        return $this->belongsTo('App\Step' );
    }
}
