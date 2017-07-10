<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Treatment extends Model
{
    protected $fillable = ['treatment', 'description'];

    public $timestamps = false;

    public function appointment()
    {
        return $this->hasMany('App\Appointment');
    }

    public function specialty()
    {
        return $this->belongsTo('App\Specialty');
    }
}
