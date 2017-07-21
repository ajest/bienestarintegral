<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PatientRequest extends FormRequest
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
            'name'          => 'required|max:255',
            'email'         => 'required|email|max:100|unique:patients,email,' . $this->id,
            'cellphone'     => 'required|max:20',
            'tel'           => 'max:20',
            'dni'           => 'max:15',
            'civil_status'  => 'max:4',
            'gender'        => 'required|max:4',
            'address'       => 'max:100',
            'birthdate'     => 'max:20',
            'area'          => 'max:20'
        ];
    }
}
