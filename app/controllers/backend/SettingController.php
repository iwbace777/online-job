<?php namespace Backend;

use Illuminate\Routing\Controllers\Controller;
use View, Input, Redirect, Session, Validator;
use \Inquirymall\Models\Setting as SettingModel;

class SettingController extends \BaseController {

    public function __construct() {
        $this->beforeFilter(function(){
            if (!Session::has('admin_id')) {
                return Redirect::route('backend.auth.login');
            }
        });
    }
    
    public function index() {
        $param['pageNo'] = 6;
        if ($alert = Session::get('alert')) {
            $param['alert'] = $alert;
        }
        $param['settings'] = SettingModel::get();
        return View::make('backend.setting.index')->with($param);
    }
    
    public function store() {   
        $rules = [];
        
        $validator = Validator::make(Input::all(), $rules);
        
        if ($validator->fails()) {
            return Redirect::back()
                ->withErrors($validator)
                ->withInput();
        } else {
            if (Input::has('setting_id')) {
                $id = Input::get('setting_id');
                $setting = SettingModel::find($id);
            } else {
                $setting = new SettingModel;                
            }            
            $package->name = Input::get('name');
            $package->price = Input::get('price');
            $package->count = Input::get('count');
            $package->save();
            
            $alert['msg'] = 'Package has been saved successfully';
            $alert['type'] = 'success';            
              
            return Redirect::route('backend.setting')->with('alert', $alert);            
        }
    }
}
