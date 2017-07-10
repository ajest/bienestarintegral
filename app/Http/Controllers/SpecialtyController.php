<?php

namespace App\Http\Controllers;

use App\Specialty;
use Illuminate\Http\Request;
use App\Http\Requests\SpecialtyRequest;
use Illuminate\Pagination\Paginator;

class SpecialtyController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Shows Series for Frontend Frameworks
     *
     */
    public function getAll($page = 1, $order = 'specialty', $order_mode = 'asc', $rows = 10){

        Paginator::currentPageResolver(function () use ($page) {
            return $page;
        });

        $allow_order = [
            'specialty',
            'description'
        ];

        if(in_array($order, $allow_order) && ($order_mode == 'asc' || $order_mode == 'desc')){
            $specialties_data = Specialty::orderBy($order, $order_mode)->paginate($rows);

            return ['specialties' => $specialties_data];
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SpecialtyRequest $request)
    {
        $specialty = new Specialty;

        $specialty->specialty    = $request->specialty;
        $specialty->description   = $request->description;

        $res = $specialty->save();

        return ['status' => 'success'];
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Specialty  $specialty
     * @return \Illuminate\Http\Response
     */
    public function show(Specialty $specialty)
    {
        return true;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Specialty  $specialty
     * @return \Illuminate\Http\Response
     */
    public function update(SpecialtyRequest $request, Specialty $specialty)
    {
        $specialty->specialty   = $request->specialty;
        $specialty->description = $request->description;

        $res = $specialty->save();

        return ['status' => 'success'];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Specialty  $specialty
     * @return \Illuminate\Http\Response
     */
    public function destroy(Specialty $specialty)
    {
        $res = $specialty->delete();
        return ['status' => 'success'];
    }

    public function search(){
        $term = '';
        if(!empty($_GET['term'])) $term = urldecode($_GET['term']);

        $specialties_data = Specialty::select('specialties.*')
                        ->where(function ($query) use ($term) {
                        $query
                            ->where('specialty', 'like', '%' . $term . '%')
                            ->orWhere('description', 'like', '%' . $term . '%');
                        })
                        ->get();

        return ['specialties' => $specialties_data];
    }
}
