<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    protected $fillable = ['name', 'email', 'phone', 'tel', 'dni', 'civil_status_id', 'gender_id', 'address', 'birthdate', 'area', 'facebook', 'comments'];

    public $timestamps = false;

    public function getCreatedAtAttribute($created_at) {
        $timestamp = explode(' ', $created_at);
        
        if(!empty($timestamp[0])){
        	$date_tmp = explode('-', $timestamp[0]);
        	$created_at = $date_tmp[2] . '/' . $date_tmp[1] . '/' . $date_tmp[0];
        }
        
        return $created_at;
    }

    public function appointment()
    {
        return $this->hasMany('App\Appointment');
    }

    public function gender() {
        return $this->belongsTo('App\Gender');
    }

    public function civil_status() {
        return $this->belongsTo('App\CivilStatus');
    }
}
