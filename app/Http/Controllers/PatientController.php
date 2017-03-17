<?php

namespace App\Http\Controllers;

use App\Patient;
use Illuminate\Http\Request;
use App\Http\Requests\PatientRequest;

class PatientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('directory', ['patients' => Patient::orderBy('id', 'desc')->paginate(15)]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('patient_save');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PatientRequest $request)
    {
        
        $patient = new Patient;

        $patient->name   = $request->name;
        $patient->email  = $request->email;
        $patient->tel    = $request->tel;
        $patient->gender = $request->gender;
        $patient->address= $request->address;

        $patient->save();
        return redirect('/patients');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Patient  $patient
     * @return \Illuminate\Http\Response
     */
    public function show(Patient $patient)
    {
        return view('patient_detail', ['patient' => $patient]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Patient  $patient
     * @return \Illuminate\Http\Response
     */
    public function edit(Patient $patient)
    {
        return view('patient_save', ['patient' => $patient, 'edit' => TRUE]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Patient  $patient
     * @return \Illuminate\Http\Response
     */
    public function update(PatientRequest $request, Patient $patient)
    {
        $patient->name   = $request->name;
        $patient->email  = $request->email;
        $patient->tel    = $request->tel;
        $patient->gender = $request->gender;
        $patient->address= $request->address;

        $patient->save();
        return redirect('/patients');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Patient  $patient
     * @return \Illuminate\Http\Response
     */
    public function destroy(Patient $patient)
    {
        $patient->delete();
        return redirect('/patients');
    }
}
