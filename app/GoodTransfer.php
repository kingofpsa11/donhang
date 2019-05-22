<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class GoodTransfer extends Model
{
    protected $fillable = ['id', 'receive_number', 'delivery_number', 'date', 'delivery_store', 'receive_store', 'status', 'user_id'];

    public $timestamps = true;

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function goodTransferDetails()
    {
        return $this->hasMany('App\GoodTransferDetail');
    }

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

    protected $attributes = [
        'status' => 10,
    ];
}
