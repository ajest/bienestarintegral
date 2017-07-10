<?php

namespace App\Http\Controllers;

use App\Series;
use Illuminate\Http\Request;
use App\Http\Requests\SeriesRequest;
use Illuminate\Pagination\Paginator;

class SeriesController extends Controller
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
    public function getAll($page = 1, $order = 'series', $order_mode = 'asc', $rows = 10){

        Paginator::currentPageResolver(function () use ($page) {
            return $page;
        });

        $allow_order = [
            'series',
            'cant'
        ];

        if(in_array($order, $allow_order) && ($order_mode == 'asc' || $order_mode == 'desc')){
            $series_data = Series::orderBy($order, $order_mode)->paginate($rows);

            return ['series' => $series_data];
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\SeriesRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SeriesRequest $request)
    {
        $series = new Series;

        $series->series    = $request->series;
        $series->cant   = $request->cant ? $request->cant : 0;

        $res = $series->save();

        return ['status' => 'success'];
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Series  $series
     * @return \Illuminate\Http\Response
     */
    public function show(Series $series)
    {
        return true;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\SeriesRequest  $request
     * @param  \App\Series  $series
     * @return \Illuminate\Http\Response
     */
    public function update(SeriesRequest $request, Series $series)
    {
        $series->series = $request->series;
        $series->cant   = $request->cant ? $request->cant : 0;

        $res = $series->save();

        return ['status' => 'success'];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Series  $series
     * @return \Illuminate\Http\Response
     */
    public function destroy(Series $series)
    {
        $res = $series->delete();
        return ['status' => 'success'];
    }

    public function search(){
        $term = '';
        if(!empty($_GET['term'])) $term = urldecode($_GET['term']);

        $series_data = Series::select('series.*')
                        ->where(function ($query) use ($term) {
                        $query
                            ->where('series', 'like', '%' . $term . '%')
                            ->orWhere('cant', 'like', '%' . $term . '%');
                        })
                        ->get();

        return ['series' => $series_data];
    }
}
