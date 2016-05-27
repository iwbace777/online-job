<?php namespace Backend;

use Illuminate\Routing\Controllers\Controller;
use View, Input, Redirect, Session, Validator, DB, Mail;
use User as UserModel, City as CityModel, Category as CategoryModel, SubCategory as SubCategoryModel;
use UserNote as UserNoteModel, UserSubCategory as UserSubCategoryModel, Email as EmailModel;
use Buy as BuyModel;

class UserController extends \BaseController {

    public function __construct() {
        $this->beforeFilter(function(){
            if (!Session::has('admin_id')) {
                return Redirect::route('backend.auth.login');
            }
        });
    }
    
    public function index() {        
        $q = Input::get('q');
        if ($q) {
            $users = UserModel::where('name', 'LIKE', "%$q%")
                    ->orWhere('email', 'LIKE', "%$q%")
                    ->orderBy('id', 'DESC')->paginate(PAGINATION_SIZE);
        } else {
            $users = UserModel::orderBy('id', 'DESC')
                    ->paginate(PAGINATION_SIZE);
        }
        $param['users'] = $users;
        $param['pageNo'] = 2;
        $param['q'] = $q;
        if ($alert = Session::get('alert')) {
            $param['alert'] = $alert;
        }
        
        return View::make('backend.user.index')->with($param);
    }
    
    public function create() {
        $param['pageNo'] = 2;
        $param['cities'] = CityModel::all();
        $param['categories'] = CategoryModel::get();        
        return View::make('backend.user.create')->with($param);
    }
    
    public function edit($id) {
        $param['pageNo'] = 2;
        $param['user'] = UserModel::find($id);
        $param['cities'] = CityModel::all();
        $param['categories'] = CategoryModel::all();        
        return View::make('backend.user.edit')->with($param);
    }
    
    public function store() {
        
        $rules = ['name' => 'required',
                  'email' => 'required',
                 ];
        $validator = Validator::make(Input::all(), $rules);
        
        if ($validator->fails()) {
            return Redirect::back()
                ->withErrors($validator)
                ->withInput();
        } else {
            if (Input::has('user_id')) {
                $user = UserModel::find(Input::get('user_id'));
            } else {
                $user = new UserModel;
                $user->photo = 'default.png';
            }
            
            $user->name = Input::get('name');
            $user->email = Input::get('email');
            $user->email2 = Input::get('email2');
            $user->email3 = Input::get('email3');
            $user->email4 = Input::get('email4');
            $user->email5 = Input::get('email5');
            $user->vat_id = Input::get('vat_id');
            $user->contact = Input::get('contact');
            $user->zip_code = Input::get('zip_code');            
            $user->phone = Input::get('phone');
            $user->address = Input::get('address');
            $user->description = Input::get('description');
            $user->count_connection = Input::get('count_connection');
            $user->is_business = Input::get('is_business');
            $user->is_active = TRUE;
            
            if (Input::has('city_id')) {
                $arr = explode("-", Input::get('city_id'));
                $user->city_id = $arr[0];
                if (count($arr) > 1) {
                    $user->district_id = $arr[1];
                } else {
                    $user->district_id = NULL;
                }
            } else {
                $user->city_id = NULL;
                $user->district_id = NULL;
            }            
            
            if (Input::get('password') != '') {
                $user->secure_key = md5($user->salt.Input::get('password'));
            }
                        
            if (Input::hasFile('photo')) {
                $filename = str_random(24).".".Input::file('photo')->getClientOriginalExtension();
                Input::file('photo')->move(ABS_USER_PATH, $filename);
                $user->photo = $filename;
            }
                        
            $user->save();
            
            $userId = $user->id;
            
            UserSubCategoryModel::where('user_id', $userId)->delete();
            if (Input::has('sub_category')) {
                foreach (Input::get('sub_category') as $subCategory) {
                    $subCategory = SubCategoryModel::find($subCategory);
                
                    $userSubCategory = new UserSubCategoryModel;
                    $userSubCategory->user_id = $userId;
                    $userSubCategory->category_id = $subCategory->category_id;
                    $userSubCategory->sub_category_id = $subCategory->id;
                    $userSubCategory->save();
            }
            }            
            
            $alert['msg'] = 'User has been saved successfully';
            $alert['type'] = 'success';            
              
            return Redirect::route('backend.user')->with('alert', $alert);            
        }
    }
    
    public function delete($id) {
        try {
            UserModel::find($id)->delete();
            
            $alert['msg'] = 'User has been deleted successfully';
            $alert['type'] = 'success';            
        } catch(\Exception $ex) {
            $alert['msg'] = 'This User has been already used';
            $alert['type'] = 'danger';
        }

        return Redirect::route('backend.user')->with('alert', $alert);
    }
    
    public function doNote() {
        $userId = Input::get('user_id');
        $userNote = new UserNoteModel;
        $userNote->user_id = $userId;
        $userNote->description = Input::get('note');
        $userNote->save();
        return Redirect::route('backend.user.edit', $userId);
    }
    
    public function addConnection() {
        $userId = Input::get('user_id');
        $countConnection = Input::get('count_connection');
        
        $user = UserModel::find($userId);
        $user->count_connection = $user->count_connection + $countConnection;
        $user->save();
        
        $buy = new BuyModel;
        $buy->user_id = $userId;
        $buy->count = $countConnection;
        $buy->is_paid = TRUE;
        $buy->is_sent_invoice = TRUE;
        $buy->invoice_no = strtoupper(str_random(6));
        $buy->save();
        
        $total = $countConnection * CONNECTION_PRICE;
        $amount = round($total - $total / (1 + VAT_PERCENT * 0.01), 2);
        $tax = round($total - $amount, 2);
        
        $email = EmailModel::where('code', 'ET08')->firstOrFail();
        $body = $email->body;
        $body = str_replace('{invoiceNo}', $buy->invoice_no, $body);
        $body = str_replace('{name}', "Purchase Bid Request", $body);
        $body = str_replace('{amount}', $amount, $body);
        $body = str_replace('{tax}', $tax, $body);
        $body = str_replace('{total}', $total, $body);
        $body = str_replace('{date}', $buy->updated_at, $body);
        
        $info = [ 'reply_name'  => INVOICE_NAME,
                  'reply_email' => INVOICE_EMAIL,
                  'email'       => $user->email,
                  'name'        => $user->name,
                  'subject'     => SITE_NAME." Invoice",
        ];
        
        $data = ['body' => $body];
        
        Mail::send('email.blank', $data, function($message) use ($info) {
            $message->from($info['reply_email'], $info['reply_name']);
            $message->to($info['email'], $info['name'])
            ->subject($info['subject']);
        });
                
        return Redirect::back();
    }
}
