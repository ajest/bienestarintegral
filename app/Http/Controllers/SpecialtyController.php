<?php

namespace App\Http\Controllers;

use App\Specialty;
use App\Http\Requests\SpecialtyRequest;
use Illuminate\Pagination\Paginator;

class SpecialtyController extends Controller
{
    /**
     * Shows Specialties for Frontend Frameworks
     *
     * @param  integer $page
     * @param  string  $order
     * @param  string  $order_mode
     * @param  integer $rows
     * @return JSON
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
            $term = '';
            if(!empty($_GET['term'])) $term = urldecode($_GET['term']);

            $specialties_data = Specialty::select('specialties.*')
                ->where(function ($query) use ($term) {
                $query
                    ->where('specialty', 'like', '%' . $term . '%')
                    ->orWhere('description', 'like', '%' . $term . '%');
                })
                ->orderBy($order, $order_mode)
                ->paginate($rows);

            return response()->json(['specialties' => $specialties_data], 200);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\SpecialtyRequest  $request
     * @return JSON
     */
    public function store(SpecialtyRequest $request)
    {
        $specialty = new Specialty;

        $specialty->specialty    = $request->specialty;
        $specialty->description   = $request->description;

        $res = $specialty->save();

        return response()->json(['status' => 'success', 'specialty' => $specialty], 201);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\SpecialtyRequest  $request
     * @param  \App\Specialty  $specialty
     * @return JSON
     */
    public function update(SpecialtyRequest $request, Specialty $specialty)
    {
        $specialty->specialty   = $request->specialty;
        $specialty->description = $request->description;

        $res = $specialty->save();

        return response()->json(['status' => 'success', 'specialty' => $specialty], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Specialty  $specialty
     * @return JSON
     */
    public function destroy(Specialty $specialty)
    {
        $res = $specialty->delete();

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

        $specialties_data = Specialty::select('specialties.*')
                        ->where(function ($query) use ($term) {
                        $query
                            ->where('specialty', 'like', '%' . $term . '%')
                            ->orWhere('description', 'like', '%' . $term . '%');
                        })
                        ->paginate($rows);

        return response()->json(['specialties' => $specialties_data], 200);
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
