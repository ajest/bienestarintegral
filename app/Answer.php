<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    protected $fillable = ['patient_id', 'question_id', 'answer'];

    public $timestamps = false;

    public function patient(){
    	return $this->belongsTo('App\Patient');
    }

    public function question(){
    	return $this->belongsTo('App\Question');
    }
}
