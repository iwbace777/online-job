<?php namespace Backend;

use Illuminate\Routing\Controllers\Controller;
use View, Input, Redirect, Session, Validator;
use Answer as AnswerModel, Question as QuestionModel;

class AnswerController extends \BaseController {

    public function __construct() {
        $this->beforeFilter(function(){
            if (!Session::has('admin_id')) {
                return Redirect::route('backend.auth.login');
            }
        });
    }
    
    public function create($id) {
        $param['pageNo'] = 5;
        $param['question'] = QuestionModel::find($id);
        return View::make('backend.category.answer.create')->with($param);
    }    
    
    public function edit($id) {
        $param['pageNo'] = 5;
        $param['answer'] = AnswerModel::find($id);
        return View::make('backend.category.answer.edit')->with($param);
    }
    
    public function store() {
        
        $rules = ['name' => 'required'];
        $validator = Validator::make(Input::all(), $rules);
        
        if ($validator->fails()) {
            return Redirect::back()
                ->withErrors($validator)
                ->withInput();
        } else {
            if (Input::has('answer_id')) {
                $answer = AnswerModel::find(Input::get('answer_id'));
            } else {
                $answer = new AnswerModel;
                $answer->question_id = Input::get('question_id');
            }
            $answer->name = Input::get('name');
            $answer->name2 = Input::get('name2');
            $answer->order = Input::get('order');
            $answer->save();

            $alert['msg'] = 'Answer has been saved successfully';
            $alert['type'] = 'success';
              
            return Redirect::route('backend.category.question.edit', $answer->question_id)->with('alert', $alert);            
        }
    }
    
    public function delete($id) {
        try {
            $answer = AnswerModel::find($id);
            $question_id = $answer->question_id;
            $answer->delete();
            
            $alert['msg'] = 'Answer has been deleted successfully';
            $alert['type'] = 'success';            
        } catch(\Exception $ex) {
            $alert['msg'] = 'This Answer has been already used';
            $alert['type'] = 'danger';
        }

        return Redirect::route('backend.category.question.edit', $question_id)->with('alert', $alert);
    }
}
