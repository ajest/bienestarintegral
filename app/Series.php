<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Series extends Model
{
    protected $fillable = ['series', 'cant'];

    public $timestamps = false;

    public function appointment()
    {
        return $this->hasMany('App\Appointment');
    }
}
