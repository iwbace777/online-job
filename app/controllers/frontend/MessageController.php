<?php namespace Frontend;

use Illuminate\Routing\Controllers\Controller;
use View, Input, Redirect, Session, Validator, Response, Mail, DB;
use \Inquirymall\Models\Message as MessageModel, Bid as BidModel, Email as EmailModel, User as UserModel, \Inquirymall\Models\Job as JobModel;

class MessageController extends \BaseController {
    
    public function __construct() {
        $this->beforeFilter(function(){
            if (!Session::has('user_id')) {
                return Redirect::route('home.index');
            }
        });
    }
    
    public function history() {
        $param['pageNo'] = 4;
        $param['subPageNo'] = 13;
        $senderId = Session::get('user_id');
        
        $prefix = DB::getTablePrefix();
        $senderId = Session::get('user_id');
         
        $messages = array();
        $list1 = MessageModel::where('sender_id', $senderId)
                            ->where('is_origin', true)
                            ->groupBy('job_id', 'receiver_id')
                            ->get();
        foreach ($list1 as $item) {
            $count_new = MessageModel::where('sender_id', $item->receiver_id)
                                    ->where('job_id', $item->job_id)
                                    ->where('is_read', false)
                                    ->count();            
            $item->setSender(1);
            $item->setNew($count_new);
            $messages[] = $item;
        }
        
        $list2 = MessageModel::where('receiver_id', $senderId)
                            ->where('is_origin', true)
                            ->groupBy('job_id', 'sender_id')
                            ->get();
        foreach ($list2 as $item) {
            $count_new = MessageModel::where('receiver_id', $senderId)
                                    ->where('job_id', $item->job_id)
                                    ->where('is_read', false)
                                    ->count();
            $item->setSender(0);
            $item->setNew($count_new);
            $messages[] = $item;
        }
        
        for ($i = 0; $i < count($messages); $i++) {
            for ($j = $i + 1; $j < count($messages); $j++) {
                if ($messages[$i]->id < $messages[$j]->id) {
                    $temp = $messages[$i];
                    $messages[$i] = $messages[$j];
                    $messages[$j] = $temp;
                }
            }
        }

        $param['messages'] = $messages;
        return View::make('frontend.message.history')->with($param);
    }
    
    public function detail($jobId, $senderId, $receiverId) {
        $param['pageNo'] = 4;
        $param['subPageNo'] = 13;

        $prefix = DB::getTablePrefix();
        
        $sql = "SELECT sender_id, receiver_id, job_id, message, 1 as is_sender, created_at
                  FROM ".$prefix."message
                 WHERE sender_id = $senderId
                   AND receiver_id = $receiverId
                   AND job_id = $jobId
                 UNION ALL
                SELECT sender_id, receiver_id, job_id, message, 0 as is_sender, created_at
                  FROM ".$prefix."message
                 WHERE sender_id = $receiverId
                   AND receiver_id = $senderId
                   AND job_id = $jobId";
        
        $sql = "SELECT t1.*, t2.name as sender_name, t2.slug as sender_slug, t2.photo as sender_photo, t3.name as receiver_name, t3.slug as receiver_slug, t3.photo as receiver_photo
                  FROM ($sql) as t1, ".$prefix."user t2, ".$prefix."user t3
                 WHERE t1.sender_id = t2.id
                   AND t1.receiver_id = t3.id
                 ORDER BY t1.created_at DESC";
        $param['messages'] = DB::select($sql);
        $param['jobId'] = $jobId;
        $param['senderId'] = $senderId;
        $param['receiverId'] = $receiverId;
        
        $messages = MessageModel::where('job_id', $jobId)
                            ->where('sender_id', $receiverId)
                            ->where('receiver_id', $senderId)->get();
        foreach ($messages as $message) {
            $message->is_read = TRUE;
            $message->save();
        }
        
        return View::make('frontend.message.detail')->with($param);
    }
    
    public function send($jobId, $senderId, $receiverId) {
        $this->fnDoSend($jobId, $senderId, $receiverId, Input::get('message'));
        return Redirect::route('message.detail', array($jobId, $senderId, $receiverId) );
    }
    
    public function asyncSend() {
        $senderId = Input::get('sender_id');
        $receiverId = Input::get('receiver_id');
        $jobId = Input::get('job_id');
        $msg = Input::get('message');
        
        $result = $this->fnDoSend($jobId, $senderId, $receiverId, $msg);
        
        return Response::json($result);
    }
    
    public function fnDoSend($jobId, $senderId, $receiverId, $msg) {
        $count = MessageModel::where('job_id', $jobId)
                            ->where('sender_id', $senderId)
                            ->where('receiver_id', $receiverId)
                            ->count()
               + MessageModel::where('job_id', $jobId)
                            ->where('sender_id', $receiverId)
                            ->where('receiver_id', $senderId)
                            ->count();
        
        // if ($count == 0) {
            $job = JobModel::find($jobId);
            $sender = UserModel::find($senderId);
            $is_bidded = (BidModel::where('job_id', $jobId)->where('user_id', $senderId)->get()->count() > 0) ? TRUE : FALSE;
            if ($is_bidded) {

            } else {
                if ($sender->count_connection > 0) {
                    $sender->count_connection = $sender->count_connection - 1;
                    $sender->save();
                } else {
                    $alert['result'] = 'failed';
                    $alert['msg'] = trans('message.error_not_enough_bids');
                    return $alert;
                }
            }
        // }
        
        $message = new MessageModel;
        $message->job_id = $jobId;
        $message->sender_id = $senderId;
        $message->receiver_id = $receiverId;
        $message->message = $msg;
        $message->is_origin = ($count > 0) ? FALSE : TRUE;
        $message->save();
        
        $receiver = UserModel::find($receiverId);
        $sender = UserModel::find($senderId);
        
        $email = EmailModel::where('code', 'ET05')->firstOrFail();
        
        $body = str_replace( '{sender_link}', HTTP_HOST."/user/detail/".$sender->slug, $email->body);
        $body = str_replace( '{sender_name}', $sender->name, $body);
        $body = str_replace( '{message_link}', HTTP_HOST."/message/detail/$jobId/$receiverId/$senderId", $body);
        $data = ['body' => $body];
        
        $info = [ 'reply_name'  => ($email->reply_name == '') ? REPLY_NAME : $email->reply_name,
                  'reply_email' => ($email->reply_email == '') ? REPLY_EMAIL : $email->reply_email,
                  'email'       => $receiver->email,
                  'name'        => $receiver->name,
                  'subject'     => $email->subject,
                ];
        
        Mail::send('email.blank', $data, function($message) use ($info) {
            $message->from($info['reply_email'], $info['reply_name']);
            $message->to($info['email'], $info['name'])
                    ->subject($info['subject']);
        });
        
        $alert['result'] = 'success';
        $alert['msg'] = trans('message.success_sent');
        return $alert;
    }
    
    static public function countMessage() {
        return MessageModel::where('receiver_id', Session::get('user_id'))
                                ->where('is_read', false)
                                ->count();
    }
}
