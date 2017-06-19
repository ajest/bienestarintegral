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

Route::get('patients/list', 'PatientController@getall');
Route::get('patients/list/{page}', ['uses' =>'PatientController@getall'])->where('page', '[0-9]+');
Route::get('patients/detail/{patient}', function (App\Patient $patient) {
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

Route::get('appointments/list', 'AppointmentController@getall');
Route::get('appointments/list/{page}', ['uses' =>'AppointmentController@getall'])->where('page', '[0-9]+');
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
    return [
    	'appointment' 	=> $appointment,
    	'patient' 		=> $appointment->patient,
    	'professional' 	=> $appointment->professional,
    	'specialty' 	=> $appointment->specialty,
    	'treatment' 	=> $appointment->treatment,
    	'series' 		=> $appointment->series,
        'all'           => [
            'professionals' => App\Professional::all(),
            'patients' => App\Patient::all(),
            'specialties' => App\Specialty::all(),
            'treatments' => App\Treatment::all(),
            'series' => App\Series::all()
        ]
	];
});

Route::post('appointments/store', 'AppointmentController@store');
Route::post('appointments/create', 'AppointmentController@store');

Route::get('/spa', function(){
	return view('vue');
});

Route::resource('appointments', 'AppointmentController');
Route::resource('patients', 'PatientController');