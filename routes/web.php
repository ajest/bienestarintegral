<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

/**
*
* PATIENTS 
*
*/
Route::get('patients/list', 'PatientController@getall');
Route::get('patients/list/{page}', ['uses' =>'PatientController@getall'])->where('page', '[0-9]+');
Route::get('patients/list/{page}/{order}', ['uses' =>'PatientController@getall'])->where('page', '[0-9]+');
Route::get('patients/search', ['uses' =>'PatientController@search']);
Route::get('patients/detail/{patient?}', function (App\Patient $patient) {
    return [
        'patient'       => $patient,
        'appointments'  => $patient->appointment
                                        ->where('date', '>=', date('Y-m-d'))
                                            ->each->professional
                                            ->each->treatment,
        'history'  => $patient->appointment
                                        ->where('date', '<', date('Y-m-d'))
                                            ->each->professional
                                            ->each->treatment
    ];
});

Route::post('patients/store', 'PatientController@store');
Route::post('patients/create', 'PatientController@store');
/* ---------------------- */




/**
*
* APPOINTMENTS
*
*/
Route::get('appointments/list', 'AppointmentController@getall');
Route::get('appointments/list/{page}', ['uses' =>'AppointmentController@getall'])->where('page', '[0-9]+');
Route::get('appointments/list/{page}/{order}', ['uses' =>'AppointmentController@getall'])->where('page', '[0-9]+');
Route::get('appointments/search', ['uses' =>'AppointmentController@search']);
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
});
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
});

Route::post('appointments/store', 'AppointmentController@store');
Route::post('appointments/create', 'AppointmentController@store');

/* --------------------- */




/**
*
*
* PROFESSIONALS
*
*/
Route::get('professionals/list', 'ProfessionalController@getall');
Route::get('professionals/list/{page}', ['uses' =>'ProfessionalController@getall'])->where('page', '[0-9]+');
Route::get('professionals/list/{page}/{order}', ['uses' =>'ProfessionalController@getall'])->where('page', '[0-9]+');
Route::get('professionals/search', ['uses' =>'ProfessionalController@search']);
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
});

Route::post('professionals/store', 'ProfessionalController@store');
Route::post('professionals/create', 'ProfessionalController@store');
/* ---------------------------- */





Route::resource('appointments', 'AppointmentController');
Route::resource('patients', 'PatientController');
Route::resource('professionals', 'ProfessionalController');