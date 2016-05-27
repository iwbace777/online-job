<?php namespace Backend;

use Illuminate\Routing\Controllers\Controller;
use View, Input, Redirect, Session, Validator, Response, URL;
use Email as EmailModel, EmailHistory as EmailHistoryModel, \Inquirymall\Models\Job as JobModel, User as UserModel;
use EmailBusiness as EmailBusinessModel;
use Business as BusinessModel;
use SendGrid;

class EmailController extends \BaseController {

    public function __construct() {
        $this->beforeFilter(function(){
            if (!Session::has('admin_id')) {
                return Redirect::route('backend.auth.login');
            }
        });
    }
    
    public function index() {
        $param['emails'] = EmailModel::get();
        $param['pageNo'] = 7;
        if ($alert = Session::get('alert')) {
            $param['alert'] = $alert;
        }
        
        return View::make('backend.email.index')->with($param);
    }
    
    public function edit($id) {
        $param['pageNo'] = 7;
        $param['email'] = EmailModel::find($id);
        
        return View::make('backend.email.edit')->with($param);
    }
    
    public function store() {
        $rules = ['subject' => 'required', 
                  'name'    => 'required'];
        $validator = Validator::make(Input::all(), $rules);
        
        if ($validator->fails()) {
            return Redirect::back()
                ->withErrors($validator)
                ->withInput();
        } else {
            $id = Input::get('email_id');
            $email = EmailModel::find($id);
            $email->code = Input::get('code');
            $email->name = Input::get('name');
            $email->subject = Input::get('subject');
            $email->body = Input::get('body');
            $email->reply_name = Input::get('reply_name');
            $email->reply_email = Input::get('reply_email');
            $email->save();
            
            $alert['msg'] = 'Email has been updated successfully';
            $alert['type'] = 'success';            
              
            return Redirect::route('backend.email')->with('alert', $alert);            
        }
    }
    
    public function resend() {
        $id = Input::get('id');
        $emailHistory = EmailHistoryModel::find($id);
        
        $user = UserModel::find($emailHistory->user_id);
        $job = JobModel::find($emailHistory->job_id);
        
        $email = EmailModel::where('code', 'ET01')->firstOrFail();
        $data = ['body' => str_replace('{job_link}', URL::route('job.detail', $job->slug), $email->body)];        
        
        $sendgrid = new SendGrid(SENDGRID_USER, SENDGRID_PASS, array("turn_off_ssl_verification" => true));
        $mail = new SendGrid\Email();
        
        $receivers = array();
        $receivers[] = $user->email;
        
        if ($user->email2 != '') {
            $receivers[] = $user->email2;
        }
        if ($user->email3 != '') {
            $receivers[] = $user->email3;
        }
        if ($user->email4 != '') {
            $receivers[] = $user->email4;
        }
        if ($user->email5 != '') {
            $receivers[] = $user->email5;
        }

        $mail->setTos($receivers)
             ->setFrom(($email->reply_email == '') ? REPLY_EMAIL : $email->reply_email)
             ->setFromName(($email->reply_name == '') ? REPLY_NAME : $email->reply_name)
             ->setSubject($email->subject)
             ->setHtml($data['body'])
             ->addCategory($emailHistory->token);
        $sendgrid->send($mail);
        
        $emailHistory->is_read = FALSE;
        $emailHistory->save(); 
        return Response::json(['result' => 'success', 'msg' => 'The newsletter has been resent successfully']);        
    }
    
    public function resubscribe() {
        $id = Input::get('id');
        $emailBusiness = EmailBusinessModel::find($id);
        
        $business = BusinessModel::find($emailBusiness->business_id);
        $job = JobModel::find($emailBusiness->job_id);
        
        $email = EmailModel::where('code', 'ET01')->firstOrFail();
        $data = ['body' => str_replace('{job_link}', URL::route('job.detail', $job->slug), $email->body)];
        
        $sendgrid = new SendGrid(SENDGRID_USER, SENDGRID_PASS, array("turn_off_ssl_verification" => true));
        $mail = new SendGrid\Email();
        
        $receivers = array();
        $receivers[] = $business->email;
        
        if ($business->email2 != '') {
            $receivers[] = $business->email2;
        }
        if ($business->email3 != '') {
            $receivers[] = $business->email3;
        }
        if ($business->email4 != '') {
            $receivers[] = $business->email4;
        }
        if ($business->email5 != '') {
            $receivers[] = $business->email5;
        }
        
        $mail->setTos($receivers)
                    ->setFrom(($email->reply_email == '') ? REPLY_EMAIL : $email->reply_email)
                    ->setFromName(($email->reply_name == '') ? REPLY_NAME : $email->reply_name)
                    ->setSubject($email->subject)
                    ->setHtml($data['body'])
                    ->addCategory($emailBusiness->token);
        $sendgrid->send($mail);
        
        $emailBusiness->is_read = FALSE;
        $emailBusiness->save();
        return Response::json(['result' => 'success', 'msg' => 'The newsletter has been resent successfully']);        
    }
}
