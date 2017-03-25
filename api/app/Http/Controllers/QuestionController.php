<?php

namespace App\Http\Controllers;

use App\Question;
use App\QuestionOptions;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    #Add question
    public function addQuestion(Request $request)
    {

        /*Retrieve request & Validate*/
        $data=$this->getData($request->all(),Question::DATA);
        //return $data;

        $check=$this->RequestValidate($data,[
            'title' => 'required|max:255',
            'bank_id' => 'required|numeric|exists:banks,id'
        ]);

        if (!$check) return $this->response();

        $question=Question::create($data);
        $this->successData('message','Survey question added');
        $this->successData('question',$question);
        return $this->response();
    }

    #get question
    public function getQuestion(Request $request,$id)
    {
        $data=Question::where('id',$id)->with('options')->first();
        $this->successData('question',$data);
        return $this->response();
    }

    #get All questions
    public function getAllQuestion(Request $request)
    {
        $data=Question::with('options')->paginate(20);
        $this->successData('question',$data);
        return $this->response();
    }

    #delete question
    public function deleteQuestion(Request $request,$id)
    {
        $data=Question::where('id',$id)->delete();
        $this->successData('message','Question Deleted');
        return $this->response();

    }

    #Add question Options
    public function addQuestionOptions(Request $request)
    {
        /*Retrieve request & Validate*/
        $data=$this->getData($request->all(),QuestionOptions::DATA);
        //return $data;

        $check=$this->RequestValidate($data,[
            'option' => 'required|max:255',
            'question_id' => 'required|numeric|exists:questions,id'
        ]);

        if (!$check) return $this->response();

        $option=QuestionOptions::create($data);
        $this->successData('message','question option added');
        $this->successData('option',$option);
        return $this->response();

    }

    #delete question option
    public function deleteQuestionOption(Request $request,$id)
    {
        $data=QuestionOptions::where('id',$id)->delete();
        $this->successData('message','Question Option Deleted');
        return $this->response();
    }

    #@todo get question responses
    public function questionResponse(Request $request,$id)
    {

        return $this->response();
    }
}
