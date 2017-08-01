<?php

namespace App\Http\Controllers;

use App\Question;
use App\Http\Requests\QuestionRequest;
use Illuminate\Pagination\Paginator;

class QuestionController extends Controller
{
    /**
     * Shows Questions for Frontend Frameworks
     *
     * @param  integer $page
     * @param  string  $order
     * @param  string  $order_mode
     * @param  integer $rows
     * @return JSON
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

            return response()->json(['questions' => $question_data], 200);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\QuestionRequest  $request
     * @return JSON
     */
    public function store(QuestionRequest $request)
    {
        $question = new Question;

        $question->question    = $request->question;
        $question->specialty_id = $request->specialty_id;

        $res = $question->save();

        return response()->json(['status' => 'success', 'question' => $question], 201);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\QuestionRequest  $request
     * @param  \App\Question  $question
     * @return JSON
     */
    public function update(QuestionRequest $request, Question $question)
    {
        $question->question    = $request->question;
        $question->specialty_id = $request->specialty_id;

        $res = $question->save();

        return response()->json(['status' => 'success', 'question' => $question], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Question  $question
     * @return JSON
     */
    public function destroy(Question $question)
    {
        $res = $question->delete();

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

        return response()->json(['questions' => $questions_data], 200);
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
