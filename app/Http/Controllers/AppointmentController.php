<?php

namespace App\Http\Controllers;

use App\Appointment;
use App\Http\Requests\AppointmentRequest;
use Illuminate\Pagination\Paginator;

class AppointmentController extends Controller
{
    /**
     * Shows Appointments for Frontend Frameworks
     *
     * @param  integer $page
     * @param  string  $order
     * @param  string  $order_mode
     * @param  integer $rows
     * @return JSON
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
            
            return response()->json(['appointments' => $appointment_data], 200);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\AppointmentRequest  $request
     * @return JSON
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
        
        return response()->json(['status' => 'success', 'appointment' => $appointment], 201);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\AppointmentRequest  $request
     * @param  \App\Appointment  $appointment
     * @return JSON
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
        $appointment->comments          = $request->comments;

        $res = $appointment->save();

        return response()->json(['status' => 'success', 'appointment' => $appointment], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Appointment  $appointment
     * @return JSON
     */
    public function destroy(Appointment $appointment)
    {        
        $paciente = $appointment->patient->name;

        $res = $appointment->delete();

        return response()->json(['status' => 'success'], 200);
    }

    /**
     * Search for a term in indicated table.
     *
     * @param  integer $page
     * @param  integer $rows
     * @return JSON
     */
    public function search($page = 1, $rows = 10){

        Paginator::currentPageResolver(function () use ($page) {
            return $page;
        });

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
            })->paginate($rows);
            
        $lastPage = $appointment_data->lastPage();

        $appointment_data = $appointment_data->each(function ($item, $key) {
            $tmp_date = explode('-', $item->date);
            $date = $tmp_date[2] . '/' . $tmp_date[1] . '/' . $tmp_date[0];
            return $date; 
        });

        return ['appointments' => $appointment_data, 'lastPage' => $lastPage];
    }

    /**
     * Prevents error in route resources
     *
     * @return bool
     */
    public function show()
    {
        return true;
    }
}
