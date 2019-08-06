<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Step extends Model
{
    protected $fillable = ['id', 'name', 'role_id', 'step_id'];

    public $timestamps = true;

    public function stepBefore()
    {
        return $this->hasOne('App\Step', 'id', 'step_id');
    }
}
