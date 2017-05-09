<?php

namespace App\Http\Controllers;

use App\Appointment;
use App\Professional;
use App\Patient;
use App\Specialty;
use App\Treatment;
use App\Series;
use App\Http\Requests\AppointmentRequest;
use Illuminate\Http\Request;

class AppointmentController extends Controller
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
        return view('appointments', ['appointments' => Appointment::orderBy('id', 'desc')->paginate(15)]);
    }

    public function getAll($page = 1){
        return ['appointments' => Appointment::with(['professional', 'patient', 'treatment'])->paginate($page * 15)];
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data_to_view = [
            'professionals' => Professional::all(),
            'patients'      => Patient::all(),
            'specialties'   => Specialty::all(),
            'treatments'    => Treatment::all(),
            'series'        => Series::all()
        ];

        return view('appointment_save', $data_to_view);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\AppointmentRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AppointmentRequest $request)
    {
        $appointment = new Appointment;

        $appointment->title             = $request->title;
        $appointment->professional_id   = $request->professional_id;
        $appointment->patient_id        = $request->patient_id;
        $appointment->specialty_id      = $request->specialty_id;
        $appointment->treatment_id      = $request->treatment_id;
        $appointment->series_id         = $request->series_id;
        $appointment->date              = $request->date;
        $appointment->hour              = $request->hour;

        $res = $appointment->save();

        $this->OperationMessage->saveOrFailMessage($res, $res ? ": El turno para el paciente " . $appointment->patient->name . " ha sido cargado exitosamente." : ": Ha ocurrido un error al cargar el turno para el paciente ". $appointment->patient->name);

        return redirect('/appointments');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Appointment  $appointment
     * @return \Illuminate\Http\Response
     */
    public function show(Appointment $appointment)
    {
        return view('appointment_detail', ['appointment' => $appointment]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Appointment  $appointment
     * @return \Illuminate\Http\Response
     */
    public function edit(Appointment $appointment)
    {
        $data_to_view = [
            'appointment'   => $appointment,
            'edit'          => TRUE,
            'professionals' => Professional::all(),
            'patients'      => Patient::all(),
            'specialties'   => Specialty::all(),
            'treatments'    => Treatment::all(),
            'series'        => Series::all()
        ];

        return view('appointment_save', $data_to_view);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Appointment  $appointment
     * @return \Illuminate\Http\Response
     */
    public function update(AppointmentRequest $request, Appointment $appointment)
    {
        $appointment->title             = $request->title;
        $appointment->professional_id   = $request->professional_id;
        $appointment->patient_id        = $request->patient_id;
        $appointment->specialty_id      = $request->specialty_id;
        $appointment->treatment_id      = $request->treatment_id;
        $appointment->series_id         = $request->series_id;
        $appointment->date              = $request->date;
        $appointment->hour              = $request->hour;

        $res = $appointment->save();

        $this->OperationMessage->saveOrFailMessage($res, $res ? ": El turno para el paciente " . $appointment->patient->name . " ha sido editado exitosamente." : ": Ha ocurrido un error al editar el turno para el paciente " . $appointment->patient->name);

        return redirect('/appointments');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Appointment  $appointment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Appointment $appointment)
    {        
        $paciente = $appointment->patient->name;

        $res = $appointment->delete();

        $this->OperationMessage->deleteMessage($res, $res ? ": El turno para el paciente $paciente ha sido eliminado permanentemente." : ": Ha ocurrido un error al eliminar el turno para el paciente $paciente.");

        return redirect('/appointments');
    }
}
