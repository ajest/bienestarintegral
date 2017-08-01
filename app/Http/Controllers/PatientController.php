<?php

namespace App\Http\Controllers;

use App\Patient;
use App\Http\Requests\PatientRequest;
use Illuminate\Pagination\Paginator;

class PatientController extends Controller
{
    /**
     * Shows Patients for Frontend Frameworks
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
            'name',
            'email',
            'cellphone',
            'tel',
            'dni',
            'civil_status_id',
            'gender_id',
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

            $patients_data = Patient::with(['gender', 'civil_status'])
                ->where(function ($query) use ($term) {
                    $query
                        ->whereRaw('DATE_FORMAT(created_at, "%d/%m/%Y") like "%' . $term . '%"')
                        ->raw(' OR DATE_FORMAT(birthdate, "%d/%m/%Y") like "%' . $term . '%"')
                        ->orWhere('name', 'like', '%' . $term . '%')
                        ->orWhere('email', 'like', '%' . $term . '%')
                        ->orWhere('cellphone', 'like', '%' . $term . '%')
                        ->orWhere('tel', 'like', '%' . $term . '%')
                        ->orWhere('dni', 'like', '%' . $term . '%')
                        ->orWhere('address', 'like', '%' . $term . '%')
                        ->orWhere('birthdate', 'like', '%' . $term . '%')
                        ->orWhere('area', 'like', '%' . $term . '%')
                        ->orWhere('facebook', 'like', '%' . $term . '%')
                        ->orWhereHas('gender', function ($query) use ($term) {
                            $query->where('gender', 'like', '%' . $term . '%');
                        })
                        ->orWhereHas('civil_status', function ($query) use ($term) {
                            $query->where('civil_status', 'like', '%' . $term . '%');
                        });
                })
                ->orderBy($order, $order_mode)
                ->paginate($rows);

            return response()->json(['patients' => $patients_data], 200);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\PatientRequest  $request
     * @return JSON
     */
    public function store(PatientRequest $request)
    {
        
        $patient = new Patient;

        $patient->name              = $request->name;
        $patient->email             = $request->email;
        $patient->cellphone         = $request->cellphone;
        $patient->tel               = $request->tel;
        $patient->dni               = $request->dni;
        $patient->civil_status_id   = $request->civil_status_id;
        $patient->gender_id         = $request->gender_id;
        $patient->address           = $request->address;
        $patient->birthdate         = $request->birthdate;
        $patient->area              = $request->area;
        $patient->facebook          = $request->facebook;
        $patient->address           = $request->address;
        $patient->comments          = $request->comments;

        $res = $patient->save();

        return response()->json(['status' => 'success', 'patient' => $patient], 201);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\PatientRequest  $request
     * @param  \App\Patient  $patient
     * @return JSON
     */
    public function update(PatientRequest $request, Patient $patient)
    {
        $patient->name              = $request->name;
        $patient->email             = $request->email;
        $patient->cellphone         = $request->cellphone;
        $patient->tel               = $request->tel;
        $patient->dni               = $request->dni;
        $patient->civil_status_id   = $request->civil_status_id;
        $patient->gender_id         = $request->gender_id;
        $patient->address           = $request->address;
        $patient->birthdate         = $request->birthdate;
        $patient->area              = $request->area;
        $patient->facebook          = $request->facebook;
        $patient->address           = $request->address;
        $patient->comments          = $request->comments;

        $res = $patient->save();

        return response()->json(['status' => 'success', 'patient' => $patient], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Patient  $patient
     * @return JSON
     */
    public function destroy(Patient $patient)
    {
        $paciente = $patient->name;

        $res = $patient->delete();

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
        if(!empty($_GET['term'])) $term = urldecode($_GET['term']);

        $patients_data = Patient::with(['gender', 'civil_status'])
                        ->select('patients.*')
                        ->where(function ($query) use ($term) {
                        $query
                            ->whereRaw('DATE_FORMAT(created_at, "%d/%m/%Y") like "%' . $term . '%"')
                            ->raw(' OR DATE_FORMAT(birthdate, "%d/%m/%Y") like "%' . $term . '%"')
                            ->orWhere('name', 'like', '%' . $term . '%')
                            ->orWhere('email', 'like', '%' . $term . '%')
                            ->orWhere('cellphone', 'like', '%' . $term . '%')
                            ->orWhere('tel', 'like', '%' . $term . '%')
                            ->orWhere('dni', 'like', '%' . $term . '%')
                            ->orWhere('address', 'like', '%' . $term . '%')
                            ->orWhere('birthdate', 'like', '%' . $term . '%')
                            ->orWhere('area', 'like', '%' . $term . '%')
                            ->orWhere('facebook', 'like', '%' . $term . '%')
                            ->orWhereHas('gender', function ($query) use ($term) {
                                $query->where('gender', 'like', '%' . $term . '%');
                            })
                            ->orWhereHas('civil_status', function ($query) use ($term) {
                                $query->where('civil_status', 'like', '%' . $term . '%');
                            });
                    })
                    ->paginate($rows);

        return response()->json(['status' => 'success', 'patients' => $patients_data], 200);
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