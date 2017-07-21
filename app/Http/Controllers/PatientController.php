<?php

namespace App\Http\Controllers;

use App\Patient;
use Illuminate\Http\Request;
use App\Http\Requests\PatientRequest;
use Illuminate\Support\Facades\Session;
use Illuminate\Pagination\Paginator;

class PatientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    private $OperationMessage;

    public function __construct(){
        $this->OperationMessage = resolve('App\Bienestarintegral\Messages\OperationMessage');
    }

    public function index()
    {
        return view('directory', ['patients' => Patient::orderBy('id', 'desc')->paginate(15)]);
    }

    /**
     * Shows Patients for Frontend Frameworks
     *
     */
    public function getAll($page = 1, $order = 'date', $order_mode = 'asc', $rows = 10){

        Paginator::currentPageResolver(function () use ($page) {
            return $page;
        });

        $allow_order = [
            'name',
            'email',
            'cellphone',
            'tel',
            'dni',
            'civil_status',
            'gender',
            'address',
            'birthdate',
            'area',
            'facebook',
            'date'
        ];

        if(in_array($order, $allow_order) && ($order_mode == 'asc' || $order_mode == 'desc')){

            if($order == 'date'){
                $order = 'created_at';
            }

            $term = '';
            if(!empty($_GET['term'])) $term = urldecode($_GET['term']);

            $patients_data = Patient::select('patients.*')
                ->where(function ($query) use ($term) {
                    $query
                        ->whereRaw('DATE_FORMAT(created_at, "%d/%m/%Y") like "%' . $term . '%"')
                        ->raw(' OR DATE_FORMAT(birthdate, "%d/%m/%Y") like "%' . $term . '%"')
                        ->orWhere('name', 'like', '%' . $term . '%')
                        ->orWhere('email', 'like', '%' . $term . '%')
                        ->orWhere('cellphone', 'like', '%' . $term . '%')
                        ->orWhere('tel', 'like', '%' . $term . '%')
                        ->orWhere('dni', 'like', '%' . $term . '%')
                        ->orWhere('civil_status', 'like', '%' . $term . '%')
                        ->orWhere('gender', 'like', '%' . $term . '%')
                        ->orWhere('address', 'like', '%' . $term . '%')
                        ->orWhere('birthdate', 'like', '%' . $term . '%')
                        ->orWhere('area', 'like', '%' . $term . '%')
                        ->orWhere('facebook', 'like', '%' . $term . '%');
                })
                ->orderBy($order, $order_mode)
                ->paginate($rows);

            return ['patients' => $patients_data];
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\PatientRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PatientRequest $request)
    {
        
        $patient = new Patient;

        $patient->name          = $request->name;
        $patient->email         = $request->email;
        $patient->cellphone     = $request->cellphone;
        $patient->tel           = $request->tel;
        $patient->dni           = $request->dni;
        $patient->civil_status  = $request->civil_status;
        $patient->gender        = $request->gender;
        $patient->address       = $request->address;
        $patient->birthdate     = $request->birthdate;
        $patient->area          = $request->area;
        $patient->facebook      = $request->facebook;
        $patient->address       = $request->address;
        $patient->comments      = $request->comments;

        $res = $patient->save();

        return ['status' => 'success'];
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
     * @param  \App\Http\Requests\PatientRequest  $request
     * @param  \App\Patient  $patient
     * @return \Illuminate\Http\Response
     */
    public function update(PatientRequest $request, Patient $patient)
    {
        $patient->name          = $request->name;
        $patient->email         = $request->email;
        $patient->cellphone     = $request->cellphone;
        $patient->tel           = $request->tel;
        $patient->dni           = $request->dni;
        $patient->civil_status  = $request->civil_status;
        $patient->gender        = $request->gender;
        $patient->address       = $request->address;
        $patient->birthdate     = $request->birthdate;
        $patient->area          = $request->area;
        $patient->facebook      = $request->facebook;
        $patient->address       = $request->address;
        $patient->comments      = $request->comments;

        $res = $patient->save();

        return ['status' => 'success'];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Patient  $patient
     * @return \Illuminate\Http\Response
     */
    public function destroy(Patient $patient)
    {
        $paciente = $patient->name;

        $res = $patient->delete();

        return ['status' => 'success'];
    }

    public function search($page = 1, $rows = 10){

        Paginator::currentPageResolver(function () use ($page) {
            return $page;
        });

        $term = '';
        if(!empty($_GET['term'])) $term = urldecode($_GET['term']);

        $patients_data = Patient::select('patients.*')
                        ->where(function ($query) use ($term) {
                        $query
                            ->whereRaw('DATE_FORMAT(created_at, "%d/%m/%Y") like "%' . $term . '%"')
                            ->raw(' OR DATE_FORMAT(birthdate, "%d/%m/%Y") like "%' . $term . '%"')
                            ->orWhere('name', 'like', '%' . $term . '%')
                            ->orWhere('email', 'like', '%' . $term . '%')
                            ->orWhere('cellphone', 'like', '%' . $term . '%')
                            ->orWhere('tel', 'like', '%' . $term . '%')
                            ->orWhere('dni', 'like', '%' . $term . '%')
                            ->orWhere('civil_status', 'like', '%' . $term . '%')
                            ->orWhere('gender', 'like', '%' . $term . '%')
                            ->orWhere('address', 'like', '%' . $term . '%')
                            ->orWhere('birthdate', 'like', '%' . $term . '%')
                            ->orWhere('area', 'like', '%' . $term . '%')
                            ->orWhere('facebook', 'like', '%' . $term . '%');
                    })
                    ->paginate($rows);

        return ['patients' => $patients_data];
    }
}