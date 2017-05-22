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

Route::post('patients/store', 'PatientController@store');
Route::post('patients/create', 'PatientController@store');

Route::get('appointments/list', 'AppointmentController@getall');
Route::get('appointments/list/{page}', ['uses' =>'AppointmentController@getall'])->where('page', '[0-9]+');

Route::post('appointments/store', 'AppointmentController@store');
Route::post('appointments/create', 'AppointmentController@store');

Route::get('/spa', function(){
	return view('vue');
});

Route::resource('appointments', 'AppointmentController');
Route::resource('patients', 'PatientController');