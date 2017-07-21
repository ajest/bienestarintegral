<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
	protected $fillable = ['question', 'specialty_id'];

	public $timestamps = false;

    public function specialty()
    {
        return $this->belongsTo('App\Specialty');
    }    
}
