<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Professional extends Authenticatable
{
	use Notifiable;

    protected $fillable = ['name', 'email', 'tel', 'gender'];

    protected $hidden = ['password', 'remember_token'];

    public function appointment()
    {
        return $this->hasMany('App\Appointment');
    }
}
