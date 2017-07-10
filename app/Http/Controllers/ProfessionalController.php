<?php

namespace App\Http\Controllers;

use App\Professional;
use Illuminate\Http\Request;
use App\Http\Requests\ProfessionalRequest;
use Illuminate\Pagination\Paginator;

class ProfessionalController extends Controller
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
     * Shows Professionals for Frontend Frameworks
     *
     */
    public function getAll($page = 1, $order = 'name', $order_mode = 'asc', $rows = 10){

        Paginator::currentPageResolver(function () use ($page) {
            return $page;
        });

        $allow_order = [
            'name',
            'email',
            'tel',
            'gender',
            'address'
        ];

        if(in_array($order, $allow_order) && ($order_mode == 'asc' || $order_mode == 'desc')){
            $professionals_data = Professional::orderBy($order, $order_mode)->paginate($rows);

            return ['professionals' => $professionals_data];
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProfessionalRequest $request)
    {
        $professional = new Professional;

        $professional->name    = $request->name;
        $professional->email   = $request->email;
        $professional->tel     = $request->tel;
        $professional->gender  = $request->gender;
        $professional->address = $request->address;

        $res = $professional->save();

        return ['status' => 'success'];
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Professional  $professional
     * @return \Illuminate\Http\Response
     */
    public function update(ProfessionalRequest $request, Professional $professional)
    {
        $professional->name   = $request->name;
        $professional->email  = $request->email;
        $professional->tel    = $request->tel;
        $professional->gender = $request->gender;
        $professional->address= $request->address;

        $res = $professional->save();

        return ['status' => 'success'];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Professional  $professional
     * @return \Illuminate\Http\Response
     */
    public function destroy(Professional $professional)
    {
        $res = $professional->delete();
        return ['status' => 'success'];
    }

    public function search(){
        $term = '';
        if(!empty($_GET['term'])) $term = urldecode($_GET['term']);

        $professionals_data = Professional::select('professionals.*')
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

        return ['professionals' => $professionals_data];
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Professional  $professional
     * @return \Illuminate\Http\Response
     */
    public function show(Professional $professional)
    {
        return true;
    }
}
