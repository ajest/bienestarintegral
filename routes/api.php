<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/**
*
* PATIENTS 
*
*/
Route::get('patients/list', 'PatientController@getall')->middleware('auth.jwt');
Route::get('patients/list/{page}', ['uses' =>'PatientController@getall'])->where('page', '[0-9]+')->middleware('auth.jwt');
Route::get('patients/list/{page}/{order}', ['uses' =>'PatientController@getall'])->where('page', '[0-9]+')->middleware('auth.jwt');
Route::get('patients/search', ['uses' =>'PatientController@search'])->middleware('auth.jwt');
Route::get('patients/detail/{patient?}', function (App\Patient $patient) {
    
    $date_tmp = explode('-', $patient->birthdate);
    $date_formated = [
        'd' => $date_tmp[0],
        'm' => $date_tmp[1],
        'y' => $date_tmp[2]
    ];

    return [
        'patient'       => $patient,        
        'patient_date'  => $date_formated,
        'appointments'  => $patient->appointment
                                        ->where('date', '>=', date('Y-m-d'))
                                            ->each->professional
                                            ->each->treatment,
        'history'  => $patient->appointment
                                        ->where('date', '<', date('Y-m-d'))
                                            ->each->professional
                                            ->each->treatment
    ];
})->middleware('auth.jwt');

Route::post('patients/store', 'PatientController@store')->middleware('auth.jwt');
Route::post('patients/create', 'PatientController@store')->middleware('auth.jwt');
/* ---------------------- */




/**
*
* APPOINTMENTS
*
*/
Route::get('appointments/list', 'AppointmentController@getall')->middleware('auth.jwt');
Route::get('appointments/list/{page}', ['uses' =>'AppointmentController@getall'])->where('page', '[0-9]+')->middleware('auth.jwt');
Route::get('appointments/list/{page}/{order}', ['uses' =>'AppointmentController@getall'])->where('page', '[0-9]+')->middleware('auth.jwt');
Route::get('appointments/search', ['uses' =>'AppointmentController@search'])->middleware('auth.jwt');
Route::get('appointments/detail', function () {
    return [
        'all' => [
            'professionals' => App\Professional::all(),
            'patients' => App\Patient::all(),
            'specialties' => App\Specialty::all(),
            'treatments' => App\Treatment::all(),
            'series' => App\Series::all()
        ]
    ];
})->middleware('auth.jwt');
Route::get('appointments/detail/{appointment}', function (App\Appointment $appointment) {
    
    $date_tmp = explode('-', $appointment->date);
    $date_formated = [
        'd' => $date_tmp[0],
        'm' => $date_tmp[1],
        'y' => $date_tmp[2]
    ];

    return [
    	'appointment' 	    => $appointment,
        'appointment_date'  => $date_formated,
    	'patient' 		    => $appointment->patient,
    	'professional' 	    => $appointment->professional,
    	'specialty' 	    => $appointment->specialty,
    	'treatment' 	    => $appointment->treatment,
    	'series' 		    => $appointment->series,
        'all'               => [
            'professionals' => App\Professional::all(),
            'patients'      => App\Patient::all(),
            'specialties'   => App\Specialty::all(),
            'treatments'    => App\Treatment::all(),
            'series'        => App\Series::all()
        ]
	];
})->middleware('auth.jwt');

Route::post('appointments/store', 'AppointmentController@store')->middleware('auth.jwt');
Route::post('appointments/create', 'AppointmentController@store')->middleware('auth.jwt');

/* --------------------- */




/**
*
*
* PROFESSIONALS
*
*/
Route::get('professionals/list', 'ProfessionalController@getall')->middleware('auth.jwt');
Route::get('professionals/list/{page}', ['uses' =>'ProfessionalController@getall'])->where('page', '[0-9]+')->middleware('auth.jwt');
Route::get('professionals/list/{page}/{order}', ['uses' =>'ProfessionalController@getall'])->where('page', '[0-9]+')->middleware('auth.jwt');
Route::get('professionals/search', ['uses' =>'ProfessionalController@search'])->middleware('auth.jwt');
Route::get('professionals/detail/{professional?}', function (App\Professional $professional) {
    return [
        'professional'  => $professional,
        'appointments'  => $professional->appointment
                                        ->where('date', '>=', date('Y-m-d'))
                                            ->each->patient
                                            ->each->treatment,
        'history'       => $professional->appointment
                                        ->where('date', '<', date('Y-m-d'))
                                            ->each->patient
                                            ->each->treatment
    ];
})->middleware('auth.jwt');

Route::post('professionals/store', 'ProfessionalController@store')->middleware('auth.jwt');
Route::post('professionals/create', 'ProfessionalController@store')->middleware('auth.jwt');
Route::post('professionals/signin', 'ProfessionalController@signin');
/* ---------------------------- */





/**
*
*
* SERIES
*
*/
Route::get('series/list', 'SeriesController@getall')->middleware('auth.jwt');
Route::get('series/list/{page}', ['uses' =>'SeriesController@getall'])->where('page', '[0-9]+')->middleware('auth.jwt');
Route::get('series/list/{page}/{order}', ['uses' =>'SeriesController@getall'])->where('page', '[0-9]+')->middleware('auth.jwt');
Route::get('series/search', ['uses' =>'SeriesController@search'])->middleware('auth.jwt');
Route::get('series/detail/{series?}', function (App\Series $series) {
    return [
        'series'  => $series,
        'appointments'  => $series->appointment
                                        ->where('date', '>=', date('Y-m-d'))
                                            ->each->patient
                                            ->each->treatment,
        'history'       => $series->appointment
                                        ->where('date', '<', date('Y-m-d'))
                                            ->each->patient
                                            ->each->treatment
    ];
})->middleware('auth.jwt');

Route::post('series/store', 'SeriesController@store')->middleware('auth.jwt');
Route::post('series/create', 'SeriesController@store')->middleware('auth.jwt');
/* ---------------------------- */




/**
*
*
* SPECIALTIES
*
*/
Route::get('specialties/list', 'SpecialtyController@getall')->middleware('auth.jwt');
Route::get('specialties/list/{page}', ['uses' =>'SpecialtyController@getall'])->where('page', '[0-9]+')->middleware('auth.jwt');
Route::get('specialties/list/{page}/{order}', ['uses' =>'SpecialtyController@getall'])->where('page', '[0-9]+')->middleware('auth.jwt');
Route::get('specialties/search', ['uses' =>'SpecialtyController@search'])->middleware('auth.jwt');
Route::get('specialties/detail/{specialty?}', function (App\Specialty $specialty) {
    return [
        'specialty'  => $specialty,
        'appointments'  => $specialty->appointment
                                        ->where('date', '>=', date('Y-m-d'))
                                            ->each->patient
                                            ->each->treatment,
        'history'       => $specialty->appointment
                                        ->where('date', '<', date('Y-m-d'))
                                            ->each->patient
                                            ->each->treatment
    ];
})->middleware('auth.jwt');

Route::post('specialties/store', 'SpecialtyController@store')->middleware('auth.jwt');
Route::post('specialties/create', 'SpecialtyController@store')->middleware('auth.jwt');
/* ---------------------------- */





/**
*
*
* TREATMENTS
*
*/
Route::get('treatments/list', 'TreatmentController@getall')->middleware('auth.jwt');
Route::get('treatments/list/{page}', ['uses' =>'TreatmentController@getall'])->where('page', '[0-9]+')->middleware('auth.jwt');
Route::get('treatments/list/{page}/{order}', ['uses' =>'TreatmentController@getall'])->where('page', '[0-9]+')->middleware('auth.jwt');
Route::get('treatments/search', ['uses' =>'TreatmentController@search'])->middleware('auth.jwt');
Route::get('treatments/detail', function () {
    return [
        'all' => [
            'specialties' => App\Specialty::all()
        ]
    ];
})->middleware('auth.jwt');
Route::get('treatments/detail/{treatment?}', function (App\Treatment $treatment) {
    return [
        'treatment'  => $treatment,
        'specialty'  => $treatment->specialty,
        'appointments'  => $treatment->appointment
                                        ->where('date', '>=', date('Y-m-d'))
                                            ->each->patient
                                            ->each->treatment,
        'history'       => $treatment->appointment
                                        ->where('date', '<', date('Y-m-d'))
                                            ->each->patient
                                            ->each->treatment,
        'all'               => [
            'specialties'   => App\Specialty::all()
        ]
    ];
})->middleware('auth.jwt');
Route::post('treatments/store', 'TreatmentController@store')->middleware('auth.jwt');
Route::post('treatments/create', 'TreatmentController@store')->middleware('auth.jwt');
/* ---------------------------- */





/**
*
*
* Questions
*
*/
Route::get('questions/list', 'QuestionController@getall')->middleware('auth.jwt');
Route::get('questions/list/{page}', ['uses' =>'QuestionController@getall'])->where('page', '[0-9]+')->middleware('auth.jwt');
Route::get('questions/list/{page}/{order}', ['uses' =>'QuestionController@getall'])->where('page', '[0-9]+')->middleware('auth.jwt');
Route::get('questions/search', ['uses' =>'QuestionController@search'])->middleware('auth.jwt');
Route::get('questions/detail', function () {
    return [
        'all' => [
            'specialties' => App\Specialty::all()
        ]
    ];
})->middleware('auth.jwt');
Route::get('questions/detail/{question?}', function (App\Question $question) {
    return [
        'question'  => $question,
        'specialty'  => $question->specialty,
        'all'               => [
            'specialties'   => App\Specialty::all()
        ]
    ];
})->middleware('auth.jwt');

Route::post('questions/store', 'QuestionController@store')->middleware('auth.jwt');
Route::post('questions/create', 'QuestionController@store')->middleware('auth.jwt');
/* ---------------------------- */

Route::resource('appointments', 'AppointmentController', ['middleware' => ['auth.jwt']]);
Route::resource('patients', 'PatientController', ['middleware' => ['auth.jwt']]);
Route::resource('professionals', 'ProfessionalController', ['middleware' => ['auth.jwt']]);
Route::resource('series', 'SeriesController', ['middleware' => ['auth.jwt']]);
Route::resource('specialties', 'SpecialtyController', ['middleware' => ['auth.jwt']]);
Route::resource('treatments', 'TreatmentController', ['middleware' => ['auth.jwt']]);
Route::resource('questions', 'QuestionController', ['middleware' => ['auth.jwt']]);