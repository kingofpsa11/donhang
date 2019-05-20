<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

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
}
