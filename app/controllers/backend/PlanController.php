<?php namespace Backend;

use Illuminate\Routing\Controllers\Controller;
use View, Input, Redirect, Session, Validator;
use Plan as PlanModel;

class PlanController extends \BaseController {

    public function __construct() {
        $this->beforeFilter(function(){
            if (!Session::has('admin_id')) {
                return Redirect::route('backend.auth.login');
            }
        });
    }
    
    public function index() {
        $param['plans'] = PlanModel::get();
        $param['pageNo'] = 14;
        if ($alert = Session::get('alert')) {
            $param['alert'] = $alert;
        }
        
        return View::make('backend.plan.index')->with($param);
    }
    
    public function create() {
        $param['pageNo'] = 14;
        return View::make('backend.plan.create')->with($param);
    }
    
    public function edit($id) {
        $param['pageNo'] = 14;
        $param['plan'] = PlanModel::find($id);
        
        return View::make('backend.plan.edit')->with($param);
    }
    
    public function store() {
        
        $rules = ['name' => 'required',
                  'price' => 'required|numeric',
                  'count' => 'required|numeric',
                  'plan_code' => 'required',
                 ];
        
        $validator = Validator::make(Input::all(), $rules);
        
        if ($validator->fails()) {
            return Redirect::back()
                ->withErrors($validator)
                ->withInput();
        } else {
            if (Input::has('plan_id')) {
                $id = Input::get('plan_id');
                $plan = PlanModel::find($id);
            } else {
                $plan = new PlanModel;                
            }
            $plan->name = Input::get('name');
            $plan->price = Input::get('price');
            $plan->count = Input::get('count');
            $plan->plan_code = Input::get('plan_code');
            $plan->save();
            
            $alert['msg'] = 'Plan has been saved successfully';
            $alert['type'] = 'success';            
              
            return Redirect::route('backend.plan')->with('alert', $alert);            
        }
    }
    
    public function delete($id) {
        try {
            PlanModel::find($id)->delete();
            
            $alert['msg'] = 'Plan has been deleted successfully';
            $alert['type'] = 'success';            
        } catch(\Exception $ex) {
            $alert['msg'] = 'This Plan has been already used';
            $alert['type'] = 'danger';
        }

        return Redirect::route('backend.plan')->with('alert', $alert);
    }
}
