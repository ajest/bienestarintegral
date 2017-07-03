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
use Illuminate\Pagination\Paginator;

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

        $day = explode('T', $request->date);
        $date = explode('Z', $request->date);
        $date = $day[0] . $date[1]; 

        $appointment->title             = $request->title;
        $appointment->professional_id   = $request->professional_id;
        $appointment->patient_id        = $request->patient_id;
        $appointment->specialty_id      = $request->specialty_id;
        $appointment->treatment_id      = $request->treatment_id;
        $appointment->series_id         = $request->series_id;
        $appointment->date              = $date;
        $appointment->hour              = $request->hour;

        $res = $appointment->save();

        $this->OperationMessage->saveOrFailMessage($res, $res ? ": El turno para el paciente " . $appointment->patient->name . " ha sido cargado exitosamente." : ": Ha ocurrido un error al cargar el turno para el paciente ". $appointment->patient->name);

        if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            return ['status' => 'success'];
        }else{
            return redirect('/appointments');
        }
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
        $date = $request->date;
        $date_tmp = explode('T', $date);
        if(count($date_tmp) > 1){
            $date = $date_tmp[0];
        } 

        $appointment->title             = $request->title;
        $appointment->professional_id   = $request->professional_id;
        $appointment->patient_id        = $request->patient_id;
        $appointment->specialty_id      = $request->specialty_id;
        $appointment->treatment_id      = $request->treatment_id;
        $appointment->series_id         = $request->series_id;
        $appointment->date              = $date;
        $appointment->hour              = $request->hour;

        $res = $appointment->save();

        $this->OperationMessage->saveOrFailMessage($res, $res ? ": El turno para el paciente " . $appointment->patient->name . " ha sido editado exitosamente." : ": Ha ocurrido un error al editar el turno para el paciente " . $appointment->patient->name);

        
        if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            return ['status' => 'success'];
        }else{
            return redirect('/appointments');
        }
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

        if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            return ['status' => 'success'];
        }else{
            return redirect('/appointments');
        }
    }

    /**
     * Shows Appointments for Frontend Frameworks
     *
     */
    public function getAll($page = 1, $order = 'date', $order_mode = 'asc', $rows = 10){

        Paginator::currentPageResolver(function () use ($page) {
            return $page;
        });

        $allow_order = [
            'title',
            'patient',
            'professional',
            'specialty',
            'treatment',
            'date'
        ];

        if(in_array($order, $allow_order) && ($order_mode == 'asc' || $order_mode == 'desc')){
            
            if($order == 'patient'){
                $order = 'pat.name';
            }
            if($order == 'professional'){
                $order = 'pro.name';
            }
            if($order == 'specialty'){
                $order = 'spe.specialty';
            }
            if($order == 'treatment'){
                $order = 'tre.treatment';
            }

            $where = '1';
            if(!empty($_GET['filtro_status'])){
                $aggregate = 'DATE(NOW())';

                if($_GET['filtro_status'] == 1){
                    $where = 'appointments.date >= ' . $aggregate;                    
                }elseif($_GET['filtro_status'] == 2){
                    $where = 'appointments.date < '  . $aggregate;
                }
            }

            $term = '';
            if(!empty($_GET['term'])) $term = $_GET['term'];

            $appointment_data = Appointment::select('appointments.*', 'appointments.id as id')
                                ->leftJoin('patients as pat', 'pat.id', '=', 'appointments.patient_id')
                                ->leftJoin('professionals as pro', 'pro.id', '=', 'appointments.professional_id')
                                ->leftJoin('specialties as spe', 'spe.id', '=', 'appointments.specialty_id')
                                ->leftJoin('treatments as tre', 'tre.id', '=', 'appointments.treatment_id')
                                ->whereRaw($where)
                                ->where(function ($query) use ($term) {
                                    if($term){
                                        $query
                                            ->where('title', 'like', '%' . $term . '%')
                                            ->orWhere('date', 'like', '%' . $term . '%')
                                            ->orWhere('hour', 'like', '%' . $term . '%')
                                            ->orWhereHas('professional', function ($query) use ($term) {
                                                $query->where('name', 'like', '%' . $term . '%');
                                            })
                                            ->orWhereHas('patient', function ($query) use ($term) {
                                                $query->where('name', 'like', '%' . $term . '%');
                                            })
                                            ->orWhereHas('treatment', function ($query) use ($term) {
                                                $query->where('treatment', 'like', '%' . $term . '%');
                                            })
                                            ->orWhereHas('specialty', function ($query) use ($term) {
                                                $query->where('specialty', 'like', '%' . $term . '%');
                                            });    
                                    }
                                })
                                ->orderBy($order, $order_mode)
                                ->with(['professional', 'patient', 'treatment', 'specialty'])
                                ->paginate($rows);
            

            foreach ($appointment_data as $key => $value) {
                $tmp_date = explode('-', $value->date);
                $date = $tmp_date[2] . '/' . $tmp_date[1] . '/' . $tmp_date[0];
                $appointment_data[$key]->date = $date;
            }

            return ['appointments' => $appointment_data];
        }
    }

    public function search(){
        $term = '';
        if(!empty($_GET['term'])) $term = $_GET['term'];
        
        $where = '1';
        if(!empty($_GET['filtro_status'])){
            $aggregate = 'DATE(NOW())';

            if($_GET['filtro_status'] == 1){
                $where = 'appointments.date >= ' . $aggregate;                    
            }elseif($_GET['filtro_status'] == 2){
                $where = 'appointments.date < '  . $aggregate;
            }
        }

        $appointment_data = Appointment::with(['professional', 'patient', 'treatment', 'specialty'])
                    ->whereRaw($where)
                    ->where(function ($query) use ($term) {
                        $query
                            ->whereRaw('DATE_FORMAT((CONCAT(date, " ", hour)), "%d/%m/%Y %H:%i") like "%' . $term . '%"')
                            ->orWhere('title', 'like', '%' . $term . '%')
                            ->orWhere('hour', 'like', '%' . $term . '%')
                            ->orWhereHas('professional', function ($query) use ($term) {
                                $query->where('name', 'like', '%' . $term . '%');
                            })
                            ->orWhereHas('patient', function ($query) use ($term) {
                                $query->where('name', 'like', '%' . $term . '%');
                            })
                            ->orWhereHas('treatment', function ($query) use ($term) {
                                $query->where('treatment', 'like', '%' . $term . '%');
                            })
                            ->orWhereHas('specialty', function ($query) use ($term) {
                                $query->where('specialty', 'like', '%' . $term . '%');
                            });
                    })
                    ->get();

        foreach ($appointment_data as $key => $value) {
            $tmp_date = explode('-', $value->date);
            $date = $tmp_date[2] . '/' . $tmp_date[1] . '/' . $tmp_date[0];
            $appointment_data[$key]->date = $date;
        }

        return ['appointments' => $appointment_data];
    }
}
