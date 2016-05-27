<?php namespace Backend;

use Illuminate\Routing\Controllers\Controller;
use View, Input, Redirect, Session, Validator;
use Package as PackageModel;

class PackageController extends \BaseController {

    public function __construct() {
        $this->beforeFilter(function(){
            if (!Session::has('admin_id')) {
                return Redirect::route('backend.auth.login');
            }
        });
    }
    
    public function index() {
        $param['packages'] = PackageModel::get();
        $param['pageNo'] = 9;
        if ($alert = Session::get('alert')) {
            $param['alert'] = $alert;
        }
        
        return View::make('backend.package.index')->with($param);
    }
    
    public function create() {
        $param['pageNo'] = 9;
        return View::make('backend.package.create')->with($param);
    }
    
    public function edit($id) {
        $param['pageNo'] = 9;
        $param['package'] = PackageModel::find($id);
        
        return View::make('backend.package.edit')->with($param);
    }
    
    public function store() {
        
        $rules = ['name' => 'required',
                  'price' => 'required|numeric',
                  'count' => 'required|numeric'
                 ];
        
        $validator = Validator::make(Input::all(), $rules);
        
        if ($validator->fails()) {
            return Redirect::back()
                ->withErrors($validator)
                ->withInput();
        } else {
            if (Input::has('package_id')) {
                $id = Input::get('package_id');
                $package = PackageModel::find($id);
            } else {
                $package = new PackageModel;                
            }            
            $package->name = Input::get('name');
            $package->price = Input::get('price');
            $package->count = Input::get('count');
            $package->save();
            
            $alert['msg'] = 'Package has been saved successfully';
            $alert['type'] = 'success';            
              
            return Redirect::route('backend.package')->with('alert', $alert);            
        }
    }
    
    public function delete($id) {
        try {
            PackageModel::find($id)->delete();
            
            $alert['msg'] = 'Package has been deleted successfully';
            $alert['type'] = 'success';            
        } catch(\Exception $ex) {
            $alert['msg'] = 'This Package has been already used';
            $alert['type'] = 'danger';
        }

        return Redirect::route('backend.package')->with('alert', $alert);
    }
}
