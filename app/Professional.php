<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Professional extends Model
{
    protected $fillable = ['name', 'email', 'tel', 'gender'];

    public function appointment()
    {
        return $this->hasMany('App\Appointment');
    }
}
