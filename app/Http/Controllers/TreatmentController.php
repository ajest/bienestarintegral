<?php

namespace App\Http\Controllers;

use App\Treatment;
use Illuminate\Http\Request;
use App\Http\Requests\TreatmentRequest;
use Illuminate\Pagination\Paginator;

class TreatmentController extends Controller
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
     * Shows Treatments for Frontend Frameworks
     *
     */
    public function getAll($page = 1, $order = 'treatment', $order_mode = 'asc', $rows = 10){

        Paginator::currentPageResolver(function () use ($page) {
            return $page;
        });

        $allow_order = [
            'treatment',
            'specialty',
            'description'
        ];

        if(in_array($order, $allow_order) && ($order_mode == 'asc' || $order_mode == 'desc')){
            
            if($order == 'specialty'){
                $order = 'spe.specialty';
            }

            $term = '';
            if(!empty($_GET['term'])) $term = $_GET['term'];

            $treatments_data = Treatment::select('treatments.*', 'treatments.id as id')
                                ->leftJoin('specialties as spe', 'spe.id', '=', 'treatments.specialty_id')
                                ->where(function ($query) use ($term) {
                                    if($term){
                                        $query
                                            ->where('treatment', 'like', '%' . $term . '%')
                                            ->orWhere('treatments.description', 'like', '%' . $term . '%')
                                            ->orWhereHas('specialty', function ($query) use ($term) {
                                                $query->where('specialty', 'like', '%' . $term . '%');
                                            });    
                                    }
                                })
                                ->with('specialty')
                                ->orderBy($order, $order_mode)
                                ->paginate($rows);

            return ['treatments' => $treatments_data];
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TreatmentRequest $request)
    {
        $treatment = new Treatment;

        $treatment->treatment    = $request->treatment;
        $treatment->specialty_id = $request->specialty_id;
        $treatment->description  = $request->description;

        $res = $treatment->save();

        return ['status' => 'success'];
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Treatment  $treatment
     * @return \Illuminate\Http\Response
     */
    public function show(Treatment $treatment)
    {
        return true;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Treatment  $treatment
     * @return \Illuminate\Http\Response
     */
    public function update(Treatment $treatment, TreatmentRequest $request)
    {
        $treatment->treatment    = $request->treatment;
        $treatment->specialty_id = $request->specialty_id;
        $treatment->description  = $request->description;

        $res = $treatment->save();

        return ['status' => 'success'];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Treatment  $treatment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Treatment $treatment)
    {
        $res = $treatment->delete();
        return ['status' => 'success'];
    }

    public function search($page = 1, $rows = 10){

        Paginator::currentPageResolver(function () use ($page) {
            return $page;
        });

        $term = '';
        if(!empty($_GET['term'])) $term = urldecode($_GET['term']);

        $treatments_data = Treatment::with('specialty')
                        ->select('treatments.*')
                        ->where(function ($query) use ($term) {
                            $query
                                ->where('treatment', 'like', '%' . $term . '%')
                                ->orWhere('description', 'like', '%' . $term . '%')
                                ->orWhereHas('specialty', function ($query) use ($term) {
                                    $query->where('specialty', 'like', '%' . $term . '%');
                                });
                    })
                    ->paginate($rows);

        return ['treatments' => $treatments_data];
    }
}
