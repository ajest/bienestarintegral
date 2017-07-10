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





/**
*
*
* SERIES
*
*/
Route::get('series/list', 'SeriesController@getall');
Route::get('series/list/{page}', ['uses' =>'SeriesController@getall'])->where('page', '[0-9]+');
Route::get('series/list/{page}/{order}', ['uses' =>'SeriesController@getall'])->where('page', '[0-9]+');
Route::get('series/search', ['uses' =>'SeriesController@search']);
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
});

Route::post('series/store', 'SeriesController@store');
Route::post('series/create', 'SeriesController@store');
/* ---------------------------- */




/**
*
*
* SPECIALTIES
*
*/
Route::get('specialties/list', 'SpecialtyController@getall');
Route::get('specialties/list/{page}', ['uses' =>'SpecialtyController@getall'])->where('page', '[0-9]+');
Route::get('specialties/list/{page}/{order}', ['uses' =>'SpecialtyController@getall'])->where('page', '[0-9]+');
Route::get('specialties/search', ['uses' =>'SpecialtyController@search']);
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
});

Route::post('specialties/store', 'SpecialtyController@store');
Route::post('specialties/create', 'SpecialtyController@store');
/* ---------------------------- */


Route::resource('appointments', 'AppointmentController');
Route::resource('patients', 'PatientController');
Route::resource('professionals', 'ProfessionalController');
Route::resource('series', 'SeriesController');
Route::resource('specialties', 'SpecialtyController');