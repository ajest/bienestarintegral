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
            'tel',
            'gender',
            'address',
            'date'
        ];

        if(in_array($order, $allow_order) && ($order_mode == 'asc' || $order_mode == 'desc')){

            if($order == 'date'){
                $order = 'created_at';
            }

            $patients_data = Patient::orderBy($order, $order_mode)->paginate($rows);

            return ['patients' => $patients_data];
        }
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
     * @param  \App\Http\Requests\PatientRequest  $request
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

        $res = $patient->save();

        $this->OperationMessage->saveOrFailMessage($res, $res ? ": El paciente $patient->name ha sido cargado exitosamente." : ": Ha ocurrido un error al cargar al paciente $patient->name.");

        if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            return ['status' => 'success'];
        }else{
            return redirect('/patients');
        }
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
        $patient->name   = $request->name;
        $patient->email  = $request->email;
        $patient->tel    = $request->tel;
        $patient->gender = $request->gender;
        $patient->address= $request->address;

        $res = $patient->save();

        $this->OperationMessage->saveOrFailMessage($res, $res ? ": El paciente $patient->name ha sido editado exitosamente." : ": Ha ocurrido un error al editar al paciente $patient->name.");

        if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            return ['status' => 'success'];
        }else{
            return redirect('/patients');
        }
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

        $this->OperationMessage->deleteMessage($res, $res ? ": El paciente $paciente ha sido eliminado del sistema permanentemente." : ": Ha ocurrido un error al eliminar el paciente $paciente del sistema.");

        if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            return ['status' => 'success'];
        }else{
            return redirect('/patients');
        }
    }

    public function search(){
        $term = '';
        if(!empty($_GET['term'])) $term = urldecode($_GET['term']);

        $patients_data = Patient::select('patients.*')
                        ->where(function ($query) use ($term) {
                        $query
                            ->whereRaw('DATE_FORMAT(created_at, "%d/%m/%Y") like "%' . $term . '%"')
                            ->orWhere('name', 'like', '%' . $term . '%')
                            ->orWhere('email', 'like', '%' . $term . '%')
                            ->orWhere('tel', 'like', '%' . $term . '%')
                            ->orWhere('gender', 'like', '%' . $term . '%')
                            ->orWhere('address', 'like', '%' . $term . '%');
                    })
                    ->get();

        return ['patients' => $patients_data];
    }
}
