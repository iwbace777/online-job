<?php namespace Backend;

use Illuminate\Routing\Controllers\Controller;
use View, Input, Redirect, Session, Validator, Excel, File, DB;
use Business as BusinessModel;
use Category as CategoryModel;
use EmailBusiness as EmailBusinessModel;
use City as CityModel;
use BusinessSubCategory as BusinessSubCategoryModel;
use SubCategory as SubCategoryModel;

class BusinessController extends \BaseController {

    public function __construct() {
        $this->beforeFilter(function(){
            if (!Session::has('admin_id')) {
                return Redirect::route('backend.auth.login');
            }
        });
    }
    
    public function index() {
        $q = Input::get('q');
        $categoryId = Input::get('category_id');
        $businesses = BusinessModel::byCategory($categoryId);
        
        if ($q) {
            $businesses = $businesses->where('name', 'LIKE', "%$q%")
                                     ->orWhere('email', 'LIKE', "%$q%");
        }
        $businesses = $businesses->orderBy('id', 'DESC')->paginate(PAGINATION_SIZE);
        
        $param['categoryId'] = $categoryId;
        $param['categories'] = CategoryModel::all();
        $param['businesses'] = $businesses;
        $param['pageNo'] = 15;
        $param['q'] = $q;
        if ($alert = Session::get('alert')) {
            $param['alert'] = $alert;
        }
        
        return View::make('backend.business.index')->with($param);
    }
    
    public function create() {
        $param['pageNo'] = 15;
        $param['categories'] = CategoryModel::get();
        $param['cities'] = CityModel::get();
        return View::make('backend.business.create')->with($param);
    }
    
    public function edit($id) {
        $param['pageNo'] = 15;
        $param['cities'] = CityModel::get();
        $param['business'] = BusinessModel::find($id);
        $param['categories'] = CategoryModel::get();
        
        $param['count_email_sent'] = EmailBusinessModel::where('business_id', $id)->count();
        $param['count_email_read'] = EmailBusinessModel::where('business_id', $id)->where('is_read', TRUE)->count();
        
        return View::make('backend.business.edit')->with($param);
    }
    
    public function store() {
        
        $rules = ['name' => 'required',
                  'email' => 'required',
                  'vat_id' => 'required',
                 ];
        $validator = Validator::make(Input::all(), $rules);
        
        if ($validator->fails()) {
            return Redirect::back()
                ->withErrors($validator)
                ->withInput();
        } else {
            if (Input::has('business_id')) {
                $business = BusinessModel::find(Input::get('business_id'));
            } else {
                $business = new BusinessModel;
            }
            
            $business->vat_id = Input::get('vat_id');
            $business->name = Input::get('name');
            $business->email = Input::get('email');
            $business->email2 = Input::get('email2');
            $business->email3 = Input::get('email3');
            $business->email4 = Input::get('email4');
            $business->email5 = Input::get('email5');
            $business->phone = Input::get('phone');
            $business->contact = Input::get('contact');
            $business->zip_code = Input::get('zip_code');
            $business->address = Input::get('address');
            $business->description = Input::get('description');
            if (Input::has('city_id')) {
                $arr = explode("-", Input::get('city_id'));
                $business->city_id = $arr[0];
                if (count($arr) > 1) {
                    $business->district_id = $arr[1];
                } else {
                    $business->district_id = NULL;
                }
            } else {
                $business->city_id = NULL;
                $business->district_id = NULL;
            }    
            
            $business->save();
            
            $businessId = $business->id;
            
            BusinessSubCategoryModel::where('business_id', $businessId)->delete();
            if (Input::has('sub_category')) {
                foreach (Input::get('sub_category') as $subCategory) {
                    $subCategory = SubCategoryModel::find($subCategory);
                
                    $businessSubCategory = new BusinessSubCategoryModel;
                    $businessSubCategory->business_id = $businessId;
                    $businessSubCategory->category_id = $subCategory->category_id;
                    $businessSubCategory->sub_category_id = $subCategory->id;
                    $businessSubCategory->save();
                }
            }            
            
            $alert['msg'] = 'Business has been saved successfully';
            $alert['type'] = 'success';            

            return Redirect::route('backend.business')->with('alert', $alert);            
        }
    }
    
    public function delete($id) {
        try {
            BusinessModel::find($id)->delete();
            
            $alert['msg'] = 'Business has been deleted successfully';
            $alert['type'] = 'success';            
        } catch(\Exception $ex) {
            $alert['msg'] = 'This Business has been already used';
            $alert['type'] = 'danger';
        }

        return Redirect::route('backend.business')->with('alert', $alert);
    }
    
    public function import() {
        $param['pageNo'] = 15;
        return View::make('backend.business.import')->with($param);
    }    
    
    public function doImport() {
        if (Input::hasFile('excel')) {
            if (strtolower(Input::file('excel')->getClientOriginalExtension()) == 'xls' || strtolower(Input::file('excel')->getClientOriginalExtension()) == 'xlsx') {
                $filename = str_random(24).".".Input::file('excel')->getClientOriginalExtension();
                Input::file('excel')->move(ABS_BUSINESS_PATH, $filename);
                
                $excel = Excel::load(ABS_BUSINESS_PATH.$filename)->get();
                $rows = $excel->toArray();
                
                foreach ($rows as $row) {
                    $cityName = trim($row['city']);
                    $cities = CityModel::whereRaw("lower(name) = lower('".$cityName."')")->get();
                    if ($cities->count() > 0 && $row['email'] != '') {
                        $business = new BusinessModel;
                        $header = ['vat_id', 'name', 'phone', 'address', 'zip_code', 'email', 'email2', 'email3', 'email4', 'email5',];
                        foreach ($header as $item) {
                            
                            $business->{$item} = $row[$item] ? $row[$item] : '';
                        }
                        
                        $business->city_id = $cities[0]->id;
                        $business->save();
                        
                        $categoryName = Input::get('category');
                        
                        $cat = CategoryModel::whereRaw("lower(name) = lower('".$categoryName."')")->get();
                        if ($cat->count() > 0) {
                            $category = $cat[0];
                            foreach ($category->subCategories as $subCategory) {
                                $businessSubCategory = new BusinessSubCategoryModel();
                                $businessSubCategory->business_id = $business->id;
                                $businessSubCategory->category_id = $subCategory->category_id;
                                $businessSubCategory->sub_category_id = $subCategory->id;
                                $businessSubCategory->save();
                            }                        
                        }
                    }
                }
                
                $alert['msg'] = 'This Business has been imported successfully';
                $alert['type'] = 'success';                
            } else {
                $alert['msg'] = 'Error occured on uploading Business';
                $alert['type'] = 'danger';                
            }
        } else {
            $alert['msg'] = 'Error occured on uploading Business';
            $alert['type'] = 'danger';            
        }

        return Redirect::route('backend.business')->with('alert', $alert);        
    }
    
    public function doImport2() {
        if (Input::hasFile('excel')) {
            if (strtolower(Input::file('excel')->getClientOriginalExtension()) == 'xls' || strtolower(Input::file('excel')->getClientOriginalExtension()) == 'xlsx') {
                $filename = str_random(24).".".Input::file('excel')->getClientOriginalExtension();
                Input::file('excel')->move(ABS_BUSINESS_PATH, $filename);
    
                $excel = Excel::load(ABS_BUSINESS_PATH.$filename)->get();
                $rows = $excel->toArray();
    
                foreach ($rows as $row) {
                    $cityName = trim($row['city']);
                    $cities = CityModel::whereRaw("lower(name) = lower('".$cityName."')")->get();
                    if ($cities->count() > 0 && $row['email'] != '') {
                        $business = new BusinessModel;
                        $header = ['vat_id', 'name', 'phone', 'address', 'zip_code', 'email', 'email2', 'email3', 'email4', 'email5',];
                        foreach ($header as $item) {
    
                            $business->{$item} = $row[$item] ? $row[$item] : '';
                        }
    
                        $business->city_id = $cities[0]->id;
                        $business->save();
    
                        $arrSubCategoryName = explode(",", Input::get('sub_category'));
                        foreach ($arrSubCategoryName as $subCategoryName) {    
                            $subCat = SubCategoryModel::whereRaw("lower(name) = lower('".$subCategoryName."')")->get();
                            if ($subCat->count() > 0) {
                                $subCategory = $subCat[0];
    
                                $businessSubCategory = new BusinessSubCategoryModel();
                                $businessSubCategory->business_id = $business->id;
                                $businessSubCategory->category_id = $subCategory->category_id;
                                $businessSubCategory->sub_category_id = $subCategory->id;
                                $businessSubCategory->save();
                                    
                            }
                        }
                    }
                }
    
                $alert['msg'] = 'This Business has been imported successfully';
                $alert['type'] = 'success';
            } else {
                $alert['msg'] = 'Error occured on uploading Business';
                $alert['type'] = 'danger';
            }
        } else {
            $alert['msg'] = 'Error occured on uploading Business';
            $alert['type'] = 'danger';
        }
    
        return Redirect::route('backend.business')->with('alert', $alert);
    }    
}
