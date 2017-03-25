<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AppointmentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title'             => 'max:255', 
            'professional_id'   => 'max:10', 
            'patient_id'        => 'required|max:10',
            'specialty_id'      => 'max:10', 
            'treatment_id'      => 'max:10', 
            'series_id'         => 'max:10', 
            'date'              => 'required|max:255', 
            'hour'              => 'required|max:255'
        ];
    }
}
