<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $fillable = ['title', 'professional_id', 'patient_id', 'specialty_id', 'treatment_id', 'series_id', 'date', 'hour', 'comments'];

    public function professional()
    {
        return $this->belongsTo('App\Professional');
    }
    
    public function patient()
    {
        return $this->belongsTo('App\Patient');
    }
    
    public function specialty()
    {
        return $this->belongsTo('App\Specialty');
    }

    public function treatment()
    {
    	return $this->belongsTo('App\Treatment');
    }

    public function series()
    {
    	return $this->belongsTo('App\Series');
    }
}
