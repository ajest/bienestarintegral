<?php

namespace App\Http\Controllers;

use App\Question;
use Illuminate\Http\Request;
use App\Http\Requests\QuestionRequest;
use Illuminate\Pagination\Paginator;

class QuestionController extends Controller
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
     * Shows Questions for Frontend Frameworks
     *
     */
    public function getAll($page = 1, $order = 'question', $order_mode = 'asc', $rows = 10){

        Paginator::currentPageResolver(function () use ($page) {
            return $page;
        });

        $allow_order = [
            'question',
            'specialty'
        ];

        if(in_array($order, $allow_order) && ($order_mode == 'asc' || $order_mode == 'desc')){
            
            $term = '';
            if(!empty($_GET['term'])) $term = $_GET['term'];

            $question_data = Question::select('questions.*', 'questions.id as id')
                                ->leftJoin('specialties as spe', 'spe.id', '=', 'questions.specialty_id')
                                ->where(function ($query) use ($term) {
                                    if($term){
                                        $query
                                            ->where('question', 'like', '%' . $term . '%')
                                            ->orWhereHas('specialty', function ($query) use ($term) {
                                                $query->where('specialty', 'like', '%' . $term . '%');
                                            });    
                                    }
                                })
                                ->with('specialty')
                                ->orderBy($order, $order_mode)
                                ->paginate($rows);

            return ['questions' => $question_data];
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(QuestionRequest $request)
    {
        $question = new Question;

        $question->question    = $request->question;
        $question->specialty_id = $request->specialty_id;

        $res = $question->save();

        return ['status' => 'success'];
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function show(Question $question)
    {
        return true;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function update(QuestionRequest $request, Question $question)
    {
        $question->question    = $request->question;
        $question->specialty_id = $request->specialty_id;

        $res = $question->save();

        return ['status' => 'success'];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function destroy(Question $question)
    {
        $res = $question->delete();
        return ['status' => 'success'];
    }

    public function search($page = 1, $rows = 10){

        Paginator::currentPageResolver(function () use ($page) {
            return $page;
        });

        $term = '';
        if(!empty($_GET['term'])) $term = urldecode($_GET['term']);

        $questions_data = Question::with('specialty')
                        ->select('questions.*')
                        ->where(function ($query) use ($term) {
                            $query
                                ->where('question', 'like', '%' . $term . '%')
                                ->orWhereHas('specialty', function ($query) use ($term) {
                                    $query->where('specialty', 'like', '%' . $term . '%');
                                });
                    })
                    ->paginate($rows);

        return ['questions' => $questions_data];
    }
}
