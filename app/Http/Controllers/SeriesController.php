<?php

namespace App\Http\Controllers;

use App\Series;
use App\Http\Requests\SeriesRequest;
use Illuminate\Pagination\Paginator;

class SeriesController extends Controller
{
    /**
     * Shows Series for Frontend Frameworks
     *
     * @param  integer $page
     * @param  string  $order
     * @param  string  $order_mode
     * @param  integer $rows
     * @return JSON
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
            $term = '';
            if(!empty($_GET['term'])) $term = urldecode($_GET['term']);

            $series_data = Series::select('series.*')
                            ->where(function ($query) use ($term) {
                            $query
                                ->where('series', 'like', '%' . $term . '%')
                                ->orWhere('cant', 'like', '%' . $term . '%');
                            })
                            ->orderBy($order, $order_mode)
                            ->paginate($rows);

            return response()->json(['series' => $series_data], 200);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\SeriesRequest  $request
     * @return JSON
     */
    public function store(SeriesRequest $request)
    {
        $series = new Series;

        $series->series    = $request->series;
        $series->cant   = $request->cant ? $request->cant : 0;

        $res = $series->save();

        return response()->json(['status' => 'success', 'series' => $series], 201);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\SeriesRequest  $request
     * @param  \App\Series  $series
     * @return JSON
     */
    public function update(SeriesRequest $request, Series $series)
    {
        $series->series = $request->series;
        $series->cant   = $request->cant ? $request->cant : 0;

        $res = $series->save();

        return response()->json(['status' => 'success', 'series' => $series], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Series  $series
     * @return JSON
     */
    public function destroy(Series $series)
    {
        $res = $series->delete();

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

        $series_data = Series::select('series.*')
                        ->where(function ($query) use ($term) {
                        $query
                            ->where('series', 'like', '%' . $term . '%')
                            ->orWhere('cant', 'like', '%' . $term . '%');
                        })
                        ->paginate($rows);

        return ['series' => $series_data];
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