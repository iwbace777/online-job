<?php namespace Backend;

use Illuminate\Routing\Controllers\Controller;
use View, Input, Redirect, Session, Validator, Queue;
use City as CityModel;
use Subscriber as SubscriberModel;

class NewsletterController extends \BaseController {

    public function __construct() {
        $this->beforeFilter(function(){
            if (!Session::has('admin_id')) {
                return Redirect::route('backend.auth.login');
            }
        });
    }
    
    public function index() {
        $param['pageNo'] = 18;
        
        $param['subscribers'] = SubscriberModel::paginate(PAGINATION_SIZE);
        
        if ($alert = Session::get('alert')) {
            $param['alert'] = $alert;
        }
        
        return View::make('backend.newsletter.index')->with($param);
    }    
    
    public function send() {
        $param['pageNo'] = 18;
        if ($alert = Session::get('alert')) {
            $param['alert'] = $alert;
        }
        return View::make('backend.newsletter.send')->with($param);
    }
    
    public function doSend() {
        $alert['msg'] = 'The newsletter has been sent successfully';
        $alert['type'] = 'success';
        
        $body = Input::get('body');
        
        Queue::push('\Inquirymall\Queue\SendNewsletter', ['body' => $body] );
        
        return Redirect::route('backend.newsletter')->with('alert', $alert);        
    }
    
    public function delete($id) {
        try {
            SubscriberModel::find($id)->delete();
    
            $alert['msg'] = 'Subscriber has been deleted successfully';
            $alert['type'] = 'success';
        } catch(\Exception $ex) {
            $alert['msg'] = 'This Subscriber has been already used';
            $alert['type'] = 'danger';
        }

        return Redirect::route('backend.newsletter')->with('alert', $alert);
    }
    
}
