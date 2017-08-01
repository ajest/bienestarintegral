<?php

namespace App\Http\Controllers;

use App\Treatment;
use App\Http\Requests\TreatmentRequest;
use Illuminate\Pagination\Paginator;

class TreatmentController extends Controller
{
    /**
     * Shows Treatments for Frontend Frameworks
     *
     * @param  integer $page
     * @param  string  $order
     * @param  string  $order_mode
     * @param  integer $rows
     * @return JSON
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

            return response()->json(['treatments' => $treatments_data], 200);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\TreatmentRequest  $request
     * @return JSON
     */
    public function store(TreatmentRequest $request)
    {
        $treatment = new Treatment;

        $treatment->treatment    = $request->treatment;
        $treatment->specialty_id = $request->specialty_id;
        $treatment->description  = $request->description;

        $res = $treatment->save();

        return response()->json(['status' => 'success', 'treatment' => $treatment], 201);
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Http\Requests\TreatmentRequest  $request
     * @param  \App\Treatment $treatment
     * @return JSON
     */
    public function update(TreatmentRequest $request, Treatment $treatment)
    {
        $treatment->treatment    = $request->treatment;
        $treatment->specialty_id = $request->specialty_id;
        $treatment->description  = $request->description;

        $res = $treatment->save();

        return response()->json(['status' => 'success', 'treatment' => $treatment], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Treatment  $treatment
     * @return JSON
     */
    public function destroy(Treatment $treatment)
    {
        $res = $treatment->delete();

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
