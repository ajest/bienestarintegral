<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $fillable = ['title', 'professional_id', 'patient_id', 'specialty_id', 'treatment_id', 'series_id', 'date', 'hour'];

    public function patient()
    {
        return $this->belongsTo('App\Patient');
    }
}
