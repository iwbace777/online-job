<?php namespace Backend;

use Illuminate\Routing\Controllers\Controller;
use View, Input, Redirect, Session, Validator, URL, Mail;
use \Inquirymall\Models\Job as JobModel;
use User as UserModel;
use Bid as BidModel;
use Email as EmailModel;

class JobController extends \BaseController {

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
            $jobs = JobModel::where('name', 'LIKE', "%$q%")
                            ->whereNotNull('user_id')
                            ->orderBy('id', 'DESC')->paginate(PAGINATION_SIZE);            
        } else {
            $jobs = JobModel::orderBy('id', 'DESC')
                            ->whereNotNull('user_id')
                            ->paginate(PAGINATION_SIZE);            
        }

        $param['jobs'] = $jobs;
        $param['pageNo'] = 4;
        $param['q'] = $q;
        if ($alert = Session::get('alert')) {
            $param['alert'] = $alert;
        }
        
        return View::make('backend.job.index')->with($param);
    }
    
    public function detail($id) {
        $param['pageNo'] = 4;
        $param['job'] = JobModel::find($id);
        $param['users'] = UserModel::all();
        return View::make('backend.job.detail')->with($param);
    }
    
    public function delete($id) {
        try {
            JobModel::find($id)->delete();
            
            $alert['msg'] = 'Job has been deleted successfully';
            $alert['type'] = 'success';            
        } catch(\Exception $ex) {
            $alert['msg'] = 'This Job has been already used';
            $alert['type'] = 'danger';
        }

        return Redirect::route('backend.job')->with('alert', $alert);
    }
    
    public function doBid() {
        
        $bid = new BidModel;
        $bid->job_id = Input::get('job_id');
        $bid->user_id = Input::get('user_id');
        $bid->price = Input::get('price');
        $bid->status = 'WAITING';
        $bid->is_admin = TRUE;
        $bid->note = Input::get('note');
        $bid->save();
        
        $job = JobModel::find(Input::get('job_id'));
        $user = UserModel::find(Input::get('user_id'));
        
        $email = EmailModel::where('code', 'ET12')->firstOrFail();
        
        $body = $email->body;
        $body = str_replace('{job_link}', URL::route('job.detail', $job->slug), $body);
        
        $info = [ 'reply_name'  => REPLY_NAME,
                  'reply_email' => REPLY_EMAIL,
                  'email'       => $user->email,
                  'name'        => $user->name,
                  'subject'     => SITE_NAME,
        ];

        $data = ['body' => $body];
        
        Mail::send('email.blank', $data, function($message) use ($info) {
            $message->from($info['reply_email'], $info['reply_name']);
            $message->to($info['email'], $info['name'])
                    ->subject($info['subject']);
        });        
        
        return Redirect::route('backend.job.detail', Input::get('job_id'));
        
    }
}
