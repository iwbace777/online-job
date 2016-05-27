<?php namespace Frontend;

use Illuminate\Routing\Controllers\Controller;
use View, Input, Redirect, Session, Validator, DB, URL, Response, Mail, Cookie, SendGrid, Paginator, Queue;
use User as UserModel, City as CityModel, Feed as FeedModel, \Inquirymall\Models\Job as JobModel;
use UserSubCategory as UserSubCategoryModel;
use BusinessSubCategory as BusinessSubCategoryModel;
use Category as CategoryModel, SubCategory as SubCategoryModel;
use Email as EmailModel, Business as BusinessModel;
use EmailBusiness as EmailBusinessModel;
use EmailHistory as EmailHistoryModel;
use Subscriber as SubscriberModel;

class UserController extends \BaseController {
    public function login() {
        if (Session::has('user_id')) {
            return Redirect::route('home.index');
        }
        $param['pageNo'] = 1;
        if ($alert = Session::get('alert')) {
            $param['alert'] = $alert;
        }
        return View::make('frontend.user.login')->with($param);
    }
    
    public function doLogin() {
        $email = Input::get('email');
        $password = Input::get('password');
        $is_remember = Input::get('is_remember');
        
        $user = UserModel::whereRaw('email = ? and secure_key = md5(concat(salt, ?))', array($email, $password))
                            ->get();
        if (count($user) != 0) {
            if ($user[0]->is_active) {
                if (Session::has('job_token')) {
                    $jobToken = Session::get('job_token');

                    $jobs = JobModel::where('token', $jobToken)->get();
                    foreach ($jobs as $job) {
                        $job->user_id = $user[0]->id;
                        $job->save();
                    }
                }
                
                Session::set('user_id', $user[0]->id);
                if ($is_remember == 1) {
                    Cookie::queue('ut', $user[0]->salt, 60 * 24 * 60);
                }
                return Redirect::route('job.dashboard');
            } else {
                $alert['msg'] = trans('user.info_verify')."<button class='btn btn-primary pull-right btn-sm' data-id=".$user[0]->id." id='js-btn-resend'>".trans('user.resend_email')."</button>";
                $alert['type'] = 'danger';
                return Redirect::route('user.login')->with('alert', $alert);                
            }

        } else {
            $alert['msg'] = trans('user.error_account');
            $alert['type'] = 'danger';
            return Redirect::route('user.login')->with('alert', $alert);
        }
    }
    
    public function signup() {
        $param['pageNo'] = 2;
        if ($alert = Session::get('alert')) {
            $param['alert'] = $alert;
        }
        $param['cities'] = CityModel::all();
        $param['categories'] = CategoryModel::all();
        return View::make('frontend.user.signup')->with($param);
    }
    
    public function doSignup() {
        
        $rules = ['email'      => 'required|email|unique:user',
                  'password'   => 'required|confirmed',
                  'password_confirmation' => 'required',
                  'name'       => 'required',
        ];
        
        $validator = Validator::make(Input::all(), $rules);
                
        if ($validator->fails()) {
            return Redirect::back()
                ->withErrors($validator)
                ->withInput();
        } else {
            $user = new UserModel;
            $user->name = Input::get('name');
            $user->email = Input::get('email');
            $user->phone = Input::get('phone');
            if (Input::has('city_id')) {
                $arr = explode("-", Input::get('city_id'));
                $user->city_id = $arr[0];
                if (count($arr) > 1) {
                    $user->district_id = $arr[1];
                }
            }
            $user->salt = str_random(8);
            $user->secure_key = md5($user->salt.Input::get('password'));
            $user->photo = 'default.png';
            $user->count_connection = FREE_CONNECTION;
            $user->is_active = FALSE;
            $user->is_business = Input::get('is_business');
            $user->vat_id = Input::get('vat_id');
            $user->save();
            
            if (Input::has('sub_category')) {
                foreach (Input::get('sub_category') as $subCategory) {
                    $subCategory = SubCategoryModel::find($subCategory);
                
                    $userSubCategory = new UserSubCategoryModel;
                    $userSubCategory->user_id = $user->id;
                    $userSubCategory->category_id = $subCategory->category_id;
                    $userSubCategory->sub_category_id = $subCategory->id;
                    $userSubCategory->save();
                }
            }             
            
            $business = BusinessModel::where('vat_id', Input::get('vat_id'))->get();
            if (count($business) > 0) {
                $business[0]->is_subscriber = FALSE;
                $business[0]->save();
            }
            
            $this->fnActiveEmail($user->id);
            
            $alert['msg'] = trans('user.info_check_email');
            $alert['type'] = 'success';
            
            return Redirect::route('user.signup')->with('alert', $alert);            
        }
    }
    
    public function profile() {
        if (!Session::has('user_id')) {
            return Redirect::route('home.index');
        }        
        
        $userId = Session::get('user_id');
        $param['pageNo'] = 4;
        $param['subPageNo'] = 17;
        $param['user'] = UserModel::find($userId);
        $param['cities'] = CityModel::all();        
        $param['categories'] = CategoryModel::all();        
        
        if ($alert = Session::get('alert')) {
            $param['alert'] = $alert;
        }
        return View::make('frontend.user.profile')->with($param);        
    }
    
    public function updateProfile() {
        $user = UserModel::find(Session::get('user_id'));
        $password = Input::get('password');
        if ($password !== '') {
            $user->secure_key = md5($user->salt.$password);
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
        
        $user->description = Input::get('description');
        if (Input::has('hourly_rate')) {
            $user->hourly_rate = Input::get('hourly_rate');
        }
        
        if (Input::hasFile('photo')) {
            $filename = str_random(24).".".Input::file('photo')->getClientOriginalExtension();
            Input::file('photo')->move(ABS_USER_PATH, $filename);
            $user->photo = $filename;
        }        
        $user->save();
        
        $userId = $user->id;        
        UserSubCategoryModel::where('user_id', Session::get('user_id'))->delete();

        if (Input::has('sub_category')) {
            foreach (Input::get('sub_category') as $subCategory) {
                $subCategory = SubCategoryModel::find($subCategory);
            
                $userSubCategory = new UserSubCategoryModel;
                $userSubCategory->user_id = Session::get('user_id');
                $userSubCategory->category_id = $subCategory->category_id;
                $userSubCategory->sub_category_id = $subCategory->id;
                $userSubCategory->save();
            }
        }        
        
        $alert['msg'] = trans('user.success_profile_update');
        $alert['type'] = 'success';
        
        return Redirect::route('user.profile')->with('alert', $alert);        
    }
    
    public function logout() {
        Session::forget('user_id');
        Cookie::queue('ut', '', -1);
        return Redirect::route('home.index');
    }
    
    public function detail($slug) {
        $param['pageNo'] = 0;
        $user = UserModel::findBySlug($slug);
        if (!$user) {
            return Redirect::route('home.index');
        }
        $param['user'] = $user;
        return View::make('frontend.user.detail')->with($param);        
    }
    
    public function active($slug) {
        $user = UserModel::where('salt', $slug)->firstOrFail();
        if ($user) {
            if (Session::has('job_token')) {
                $jobToken = Session::get('job_token');

                $jobs = JobModel::where('token', $jobToken)->get();
                foreach ($jobs as $job) {
                    $job->user_id = $user->id;
                    $job->save();
                }
            }
            
            $user->is_active = TRUE;
            $user->save();

            $alert['msg'] = trans('user.success_active_account');
            $alert['type'] = 'success';
            return Redirect::route('user.login')->with('alert', $alert);            
        }
    }
    
    public function fnActiveEmail($userId) {
        $user = UserModel::find($userId);
        $email = EmailModel::where('code', 'ET07')->firstOrFail();
        
        $data = ['body' => str_replace('{activation_link}', URL::route('user.active', $user->salt), $email->body)];
        
        $info = [ 'reply_name'  => ($email->reply_name == '') ? REPLY_NAME : $email->reply_name,
                  'reply_email' => ($email->reply_email == '') ? REPLY_EMAIL : $email->reply_email,
                  'email'       => $user->email,
                  'name'        => $user->name,
                  'subject'     => $email->subject,
                ];
        
        Mail::send('email.blank', $data, function($message) use ($info) {
            $message->from($info['reply_email'], $info['reply_name']);
            $message->to($info['email'], $info['name'])
                    ->subject($info['subject']);
        });
        
        return ['result' => 'success', 'msg' => trans('user.success_send_activation_email')];
    }
    
    public function asyncActiveEmail() {
        $userId = Input::get('user_id');
        $result = $this->fnActiveEmail($userId);
        return Response::json($result);
    }
    
    public function asyncLoadBusiness() {
        $business = BusinessModel::where('vat_id', Input::get('vat_id'))->get();
        if (count($business) > 0) {
            return Response::json(['result' => 'success', 
                                   'msg' => '',
                                   'name' => $business[0]->name,
                                   'email' => $business[0]->email,
                                   'email2' => $business[0]->email2,
                                   'email3' => $business[0]->email3,
                                   'email4' => $business[0]->email4,
                                   'email5' => $business[0]->email5,
                                   'phone' => $business[0]->phone,
                                   'contact' => $business[0]->contact,
                                   'zip_code' => $business[0]->zip_code,
                                   'address' => $business[0]->address,
                                   'description' => $business[0]->description,
                                ]);
        } else {
            return Response::json(['result' => 'failed', 'msg' => '', ]);
        }
    }
    
    public function asyncDoSubscriber() {
        $email = Input::get('email');
        $subscribers = SubscriberModel::where('email', $email)->get();
        if (count($subscribers) > 0) {
            return Response::json(['result' => 'failed', 'msg' => trans('user.already_subscriber'), ]);
        } else {
            $subscriber = new SubscriberModel;
            $subscriber->email = $email;
            $subscriber->save();
            
            return Response::json(['result' => 'success', 'msg' => trans('user.success_subscriber'), ]);
        }        
    }
    
    public function reviews() {
        if (!Session::has('user_id')) {
            return Redirect::route('home.index');
        }
        
        $userId = Session::get('user_id');
        $param['pageNo'] = 4;
        $param['subPageNo'] = 18;
        $param['user'] = UserModel::find($userId);
        
        if ($alert = Session::get('alert')) {
            $param['alert'] = $alert;
        }
        return View::make('frontend.user.reviews')->with($param);        
    }
    
    public function search() {
        $param['pageNo'] = 6;

        $name = Input::has('name') ? Input::get('name') : '';
        $type = Input::has('type') ? Input::get('type') : '';
        $category = Input::has('category') ? Input::get('category') : '';
        $city = Input::has('city') ? Input::get('city') : '';
        
        $param['uname'] = $name;
        $param['city'] = $city;
        $param['category'] = $category;
        $param['type'] = $type;
        
        $users = new UserModel;
        $users = $users->search($name, $type, $category, $city);
        
        $slice = array_slice($users, PAGINATION_SIZE * (Input::get('page', 1) - 1), PAGINATION_SIZE);

        $param['users'] = Paginator::make($slice, count($users), PAGINATION_SIZE);

        $param['categories'] = CategoryModel::orderBy('order', 'ASC')->orderBy('id', 'ASC')->get();
        $param['cities'] = CityModel::all();
        return View::make('frontend.user.search')->with($param);
    }
    
    public function forgotPassword() {
        if (Session::has('user_id')) {
            return Redirect::route('home.index');
        }
        $param['pageNo'] = 1;
        if ($alert = Session::get('alert')) {
            $param['alert'] = $alert;
        }
        return View::make('frontend.user.forgotPassword')->with($param);
    }
    
    public function sendForgotPasswordEmail() {
        $rules = ['email' => 'required|email'];
        
        $validator = Validator::make(Input::all(), $rules);
        
        if ($validator->fails()) {
            return Redirect::back()
                    ->withErrors($validator)
                    ->withInput();
        } else {
            $users = UserModel::where('email', Input::get('email'))->get();
            if (count($users) > 0) {
                $user = $users[0];
                $email = EmailModel::where('code', 'ET15')->firstOrFail();
                $data = ['body' => str_replace('{reset_link}', URL::route('user.resetPassword', $user->salt), $email->body)];
                
                $info = [ 'reply_name'  => ($email->reply_name == '') ? REPLY_NAME : $email->reply_name,
                          'reply_email' => ($email->reply_email == '') ? REPLY_EMAIL : $email->reply_email,
                          'email'       => $user->email,
                          'name'        => $user->name,
                          'subject'     => $email->subject,
                        ];
                
                Mail::send('email.blank', $data, function($message) use ($info) {
                    $message->from($info['reply_email'], $info['reply_name']);
                    $message->to($info['email'], $info['name'])
                            ->subject($info['subject']);
                });                
                
                $alert['msg'] = trans('user.reset_password_email_sent');
                $alert['type'] = 'success';
            } else {
                $alert['msg'] = trans('user.email_not_exist');
                $alert['type'] = 'danger';
            }
            return Redirect::route('user.forgotPassword')->with('alert', $alert);
        }
    }
    
    public function resetPassword($slug) {
        
        if (Session::has('user_id')) {
            return Redirect::route('home.index');
        }
        
        $users = UserModel::where('salt', $slug)->get();
        if (count($users) > 0) {
            if (Session::has('user_id')) {
                return Redirect::route('home.index');
            }
            $param['pageNo'] = 1;
            if ($alert = Session::get('alert')) {
                $param['alert'] = $alert;
            }
            $param['slug'] = $slug;
            return View::make('frontend.user.resetPassword')->with($param);            
        } else {
            $alert['msg'] = trans('user.email_not_exist');
            $alert['type'] = 'danger';
            return Redirect::route('user.forgotPassword')->with('alert', $alert);
        }
    }
    
    public function doResetPassword($slug) {
        $rules = ['password' => 'required'];
        
        $validator = Validator::make(Input::all(), $rules);
        
        if ($validator->fails()) {
            return Redirect::back()
                    ->withErrors($validator)
                    ->withInput();
        } else {
            $user = UserModel::where('salt', $slug)->firstOrFail();
            $user->secure_key = md5($user->salt.Input::get('password'));
            $user->save();
            
            $alert['msg'] = trans('user.password_reset_success');
            $alert['type'] = 'success';
            return Redirect::route('user.login')->with('alert', $alert);                        
        }        

    }
}
