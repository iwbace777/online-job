<?php namespace Backend;

use Illuminate\Routing\Controllers\Controller;
use View, Input, Redirect, Session, Validator;
use Question as QuestionModel, Category as CategoryModel, Answer as AnswerModel;
use SubCategory as SubCategoryModel;

class QuestionController extends \BaseController {

    public function __construct() {
        $this->beforeFilter(function(){
            if (!Session::has('admin_id')) {
                return Redirect::route('backend.auth.login');
            }
        });
    }
    
    public function create($id) {
        $param['pageNo'] = 5;
        $param['subCategory'] = SubCategoryModel::find($id);
        return View::make('backend.category.question.create')->with($param);
    }
    
    public function edit($id) {
        $param['pageNo'] = 5;
        $param['question'] = QuestionModel::find($id);
        return View::make('backend.category.question.edit')->with($param);
    }
    
    public function store() {
        
        $rules = ['name' => 'required'];
        $validator = Validator::make(Input::all(), $rules);
        
        if ($validator->fails()) {
            return Redirect::back()
                ->withErrors($validator)
                ->withInput();
        } else {
            $sub_category_id = Input::get('sub_category_id');
            if (Input::has('question_id')) {
                $question = QuestionModel::find(Input::get('question_id'));                                
            } else {                
                $question = new QuestionModel;
                $question->sub_category_id = $sub_category_id;      
            } 
            $question->name = Input::get('name');
            $question->name2 = Input::get('name2');
            $question->is_selectable = Input::get('is_selectable');
            $question->is_multiple = Input::get('is_multiple');
            $question->is_notable = Input::get('is_notable');
            $question->is_optional = Input::get('is_optional');
            $question->order = Input::get('order');
            $question->save();            

            $alert['msg'] = 'Question has been saved successfully';
            $alert['type'] = 'success';
              
            return Redirect::route('backend.category.sub.edit', $sub_category_id)->with('alert', $alert);            
        }
    }
    
    public function delete($id) {
        try {
            $question = QuestionModel::find($id);
            $sub_category_id = $question->sub_category_id;
            $question->delete();
            
            $alert['msg'] = 'Question has been deleted successfully';
            $alert['type'] = 'success';            
        } catch(\Exception $ex) {
            $alert['msg'] = 'This Question has been already used';
            $alert['type'] = 'danger';
        }

        return Redirect::route('backend.category.sub.edit', $sub_category_id)->with('alert', $alert);
    }
}
