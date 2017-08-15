<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Specialty extends Model
{
    protected $fillable = ['treatment', 'description'];

    public $timestamps = false;

    public function appointment()
    {
        return $this->hasMany('App\Appointment');
    }

    public function question(){
    	return $this->hasMany('App\Question');
    }
}