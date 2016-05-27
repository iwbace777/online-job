<?php namespace Backend;

use Illuminate\Routing\Controllers\Controller;
use View, Input, Redirect, Session, Validator;

class AuthController extends \BaseController {

    public function index() {
        if (Session::has('admin_id')) {
            return Redirect::route('backend.dashboard');
        } else {
            return Redirect::route('backend.auth.login');
        }
    }
    
    public function login() {
        if (Session::has('admin_id')) {
            return Redirect::route('backend.dashboard');
        }
        
        if ($alert = Session::get('alert')) {
            $param['alert'] = $alert;
            return View::make('backend.auth.login')->with($param);
        } else {
            return View::make('backend.auth.login');
        }
    }
    
    public function doLogin() {
        $name = Input::get('name');
        $password = Input::get('password');
    
        if (($name == 'mikael' && $password == 'mikael90') || ($name == 'jeni' && $password == 'jenistar90') || ($name == 'boris' && $password == 'mediatel')) {
            Session::set('admin_id', 1);
            Session::set('admin_name', $name);
            return Redirect::route('backend.dashboard');
        } else {
            $alert['msg'] = 'Invalid username and password';
            $alert['type'] = 'danger';
            return Redirect::route('backend.auth.login')->with('alert', $alert);
        }
    }
    
    public function logout() {
        Session::forget('admin_id');
        Session::forget('admin_name');
        return Redirect::route('backend.auth.login');
    }
}
