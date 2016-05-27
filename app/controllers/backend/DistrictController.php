<?php namespace Backend;

use Illuminate\Routing\Controllers\Controller;
use View, Input, Redirect, Session, Validator;
use City as CityModel;
use District as DistrictModel;

class DistrictController extends \BaseController {

    public function __construct() {
        $this->beforeFilter(function(){
            if (!Session::has('admin_id')) {
                return Redirect::route('backend.auth.login');
            }
        });
    }
    
    public function create($id) {
        $param['pageNo'] = 13;
        $param['cityId'] = $id;
        return View::make('backend.district.create')->with($param);
    }
    
    public function edit($id) {
        $param['pageNo'] = 13;
        $param['district'] = DistrictModel::find($id);       
        return View::make('backend.district.edit')->with($param);
    }
    
    public function store() {
        
        $rules = ['name' => 'required'];
        $validator = Validator::make(Input::all(), $rules);
        
        if ($validator->fails()) {
            return Redirect::back()
                ->withErrors($validator)
                ->withInput();
        } else {
            if (Input::has('district_id')) {
                $id = Input::get('district_id');
                $district = DistrictModel::find($id);
            } else {
                $district = new DistrictModel;                
            }
            $district->city_id = Input::get('city_id');
            $district->name = Input::get('name');
            $district->save();
            
            $alert['msg'] = 'District has been saved successfully';
            $alert['type'] = 'success';            
              
            return Redirect::route('backend.city.edit', Input::get('city_id'))->with('alert', $alert);            
        }
    }
    
    public function delete($id) {
        try {
            $district = DistrictModel::find($id);
            $cityId = $district->city_id;
            $district->delete();
            
            $alert['msg'] = 'District has been deleted successfully';
            $alert['type'] = 'success';            
        } catch(\Exception $ex) {
            $alert['msg'] = 'This District has been already used';
            $alert['type'] = 'danger';
        }

        return Redirect::route('backend.city.edit', $cityId)->with('alert', $alert);
    }
}
