<?php namespace Frontend;

use Illuminate\Routing\Controllers\Controller;
use View, Input, Redirect, Session, Validator, Response, Request;
use Category as CategoryModel, SubCategory as SubCategoryModel;

class CategoryController extends \BaseController {    
    public function asyncSubCategories() {
        if (Request::ajax()) {
            $categoryId = Input::get('category_id');
            $category = CategoryModel::find($categoryId);
            return Response::json(['subCategories' => $category->subCategories, 'result' => 'success', 'msg' => '', ], 200);
        }
    }
    
    public function asyncQuestions() {
        if (Request::ajax()) {
            $subCategoryId = Input::get('sub_category_id');
            $subCategory = SubCategoryModel::find($subCategoryId);
            
            $questions = array();
            foreach ($subCategory->questions as $key => $question) {
                if ($question->is_selectable) {
                    $questions[$key]['answers'] = $question->answers;
                }
                $questions[$key]['id'] = $question->id;
                $questions[$key]['name'] = $question->name;
                $questions[$key]['name2'] = $question->name2;
                $questions[$key]['is_selectable'] = $question->is_selectable;
                $questions[$key]['is_multiple'] = $question->is_multiple;
                $questions[$key]['is_notable'] = $question->is_notable;
                $questions[$key]['is_optional'] = $question->is_optional;
            }
            
            return Response::json(['questions' => $questions, 'result' => 'success', 'msg' => '', ]);            
        }
    }
}
