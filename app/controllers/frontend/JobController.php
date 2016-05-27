<?php namespace Frontend;

use Illuminate\Routing\Controllers\Controller;
use View, Input, Redirect, Session, Validator, DB, Mail, URL, Response, Cookie, SendGrid, Queue;
use Category as CategoryModel, \Inquirymall\Models\Job as JobModel, JobDetail as JobDetailModel, JobAttachment as JobAttachmentModel, Bid as BidModel;
use User as UserModel, Email as EmailModel, EmailHistory as EmailHistoryModel, City as CityModel;
use Feed as FeedModel, EmailBusiness as EmailBusinessModel, Business as BusinessModel, Rate as RateModel;
use UserSubCategory as UserSubCategoryModel, BusinessSubCategory as BusinessSubCategoryModel;
use ConnectionRequire as ConnectionRequireModel, Buy as BuyModel;

class JobController extends \BaseController {

    public function home($slug = '') {
        if ($slug != '') {
            $category = CategoryModel::findBySlug($slug);
            if (!$category){
                return Redirect::route('home.index');
            }            
            $jobs = JobModel::where('category_id', $category->id)->whereNotNull('user_id')->where('status', 'OPEN');
        } else {
            $jobs = JobModel::whereNotNull('user_id')->where('status', 'OPEN');
        }
        $param['jobs'] = $jobs->orderBy('id', 'DESC')->paginate(PAGINATION_SIZE);

        $param['pageNo'] = 0;
        $param['categories'] = CategoryModel::orderBy('order', 'ASC')->orderBy('id', 'ASC')->get();
        
        $countCategories = [];
        foreach ($param['categories'] as $category) {
            $countCategories[] = JobModel::where('category_id', $category->id)->whereNotNull('user_id')->where('status', 'OPEN')->count();
        }
        $param['count_categories'] = $countCategories;
        
        $param['count_feedback'] = FeedModel::all()->count();
        $param['count_job'] = $jobs->get()->count();
        $param['count_posted'] = JobModel::count();
        $param['count_user'] = UserModel::count();
        $param['category'] = $slug;
        return View::make('frontend.job.home')->with($param);
    }

    public function search($slug = '') {
        if ($slug != '') {
            $category = CategoryModel::findBySlug($slug);
            if (!$category) {
                return Redirect::route('home.index');
            }            
            $result = JobModel::where('category_id', $category->id)->whereNotNull('user_id')->where('status', 'OPEN');
        } else {
            $result = JobModel::whereNotNull('user_id')->where('status', 'OPEN');
        }

        $keyword = Input::has('keyword') ? Input::get('keyword') : '';

        if ($keyword != '') {
            $result->where('name', 'like', '%'.$keyword.'%');
        }

        $param['jobs'] = $result->orderBy('id', 'DESC')->paginate(PAGINATION_SIZE);
        $param['count_job'] = $result->get()->count();

        $param['pageNo'] = 5;
        $param['categories'] = CategoryModel::orderBy('order', 'ASC')->orderBy('id', 'ASC')->get();
        
        $countCategories = [];
        foreach ($param['categories'] as $category) {
            $result = JobModel::where('category_id', $category->id)->whereNotNull('user_id')->where('status', 'OPEN');
            if ($keyword != '') {
                $result->where('name', 'like', '%'.$keyword.'%');
            }            
            $countCategories[] = $result->count();
        }
        $param['count_categories'] = $countCategories;
        
        
        $param['category'] = $slug;
        $param['cities'] = CityModel::all();
        $param['keyword'] = $keyword;
        return View::make('frontend.job.search')->with($param);
    }

    public function post() {
        $param['pageNo'] = 3;
        $param['categories'] = CategoryModel::orderBy('order', 'ASC')->orderBy('id', 'ASC')->get();
        $param['cities'] = CityModel::all();
        $param['category_id'] = Input::has('category') ? Input::get('category') : '';
        return View::make('frontend.job.post')->with($param);
    }

    public function posts() {
        if (!Session::has('user_id')) {
            return Redirect::route('home.index');
        }        
        $param['pageNo'] = 4;
        $param['subPageNo'] = 15;
        $param['jobs'] = JobModel::where('user_id', Session::get('user_id'))->orderBy('id', 'DESC')->paginate(PAGINATION_SIZE);
        return View::make('frontend.job.posts')->with($param);
    }

    public function bids() {
        if (!Session::has('user_id')) {
            return Redirect::route('home.index');
        }        
        $param['pageNo'] = 4;
        $param['subPageNo'] = 14;
        $param['bids'] = BidModel::where('user_id', Session::get('user_id'))->orderBy('id', 'DESC')->paginate(PAGINATION_SIZE);
        return View::make('frontend.job.bids')->with($param);
    }

    public function dashboard() {
        if (!Session::has('user_id')) {
            return Redirect::route('home.index');
        }        
        $param['pageNo'] = 4;
        $param['subPageNo'] = 11;
        
        $param['jobs'] = JobModel::where('status', 'OPEN')->where('user_id', '!=', Session::get('user_id'))->orderBy('id', 'DESC')->paginate(PAGINATION_SIZE);
        $param['feeds'] = FeedModel::where('user_id', Session::get('user_id'))->where('is_read', FALSE)->orderBy('id', 'DESC')->get();
        $param['countMessage'] = FeedController::countFeed();
        $param['countFeed'] = MessageController::countMessage();

        $param['count_post'] = ['today' => JobModel::where('user_id', Session::get('user_id'))->whereRaw('DATE(created_at) = DATE(NOW())')->count(),
                                'week' => JobModel::where('user_id', Session::get('user_id'))->whereRaw('YEAR(created_at) = YEAR(NOW()) AND WEEK(created_at) = WEEK(NOW())')->count(),
                                'month' => JobModel::where('user_id', Session::get('user_id'))->whereRaw('YEAR(created_at) = YEAR(NOW()) AND MONTH(created_at) = MONTH(NOW())')->count(),
        ];

        $param['count_bid'] = ['today' => BidModel::where('user_id', Session::get('user_id'))->whereRaw('DATE(created_at) = DATE(NOW())')->count(),
                                'week' => BidModel::where('user_id', Session::get('user_id'))->whereRaw('YEAR(created_at) = YEAR(NOW()) AND WEEK(created_at) = WEEK(NOW())')->count(),
                                'month' => BidModel::where('user_id', Session::get('user_id'))->whereRaw('YEAR(created_at) = YEAR(NOW()) AND MONTH(created_at) = MONTH(NOW())')->count(),
        ];

        $param['count_view'] = ['today' => JobModel::where('user_id', Session::get('user_id'))->whereRaw('DATE(created_at) = DATE(NOW())')->sum('count_view'),
                                'week' => JobModel::where('user_id', Session::get('user_id'))->whereRaw('YEAR(created_at) = YEAR(NOW()) AND WEEK(created_at) = WEEK(NOW())')->sum('count_view'),
                                'month' => JobModel::where('user_id', Session::get('user_id'))->whereRaw('YEAR(created_at) = YEAR(NOW()) AND MONTH(created_at) = MONTH(NOW())')->sum('count_view'),
        ];

        $param['worth_awarded'] = DB::table('job')
                                    ->join('bid', 'job.id', '=', 'bid.job_id')
                                    ->where('job.user_id', '=', Session::get('user_id'))
                                    ->where('job.status', '=', 'PROGRESS')
                                    ->where('bid.status', '=', 'HIRED')
                                    ->sum('bid.price');

        $param['worth_active'] = BidModel::where('user_id', Session::get('user_id'))
                                        ->where('status', 'WAITING')
                                        ->sum('price');

        return View::make('frontend.job.dashboard')->with($param);
    }

    public function doPost() {
        $job = new JobModel;
        if (Session::has('user_id')) {
            $job->user_id = Session::get('user_id');
        } else {
            if (Session::has('job_token')) {
                $token = Session::get('job_token');
            } else {
                $token = str_random(32);
                Session::set('job_token', $token);
            }
            $job->token = $token;
        }
        $job->name = Input::get('name');
        $job->category_id = Input::get('category_id');
        $job->sub_category_id = Input::get('sub_category_id');
        $job->status = 'OPEN';
        $job->description = Input::get('description');
        
        if (Input::has('city_id')) {
            $arr = explode("-", Input::get('city_id'));
            $job->city_id = $arr[0];
            if (count($arr) > 1) {
                $job->district_id = $arr[1];
            } else {
                $job->district_id = NULL;
            }
        }
        
        $job->save();
        $jobId = $job->id;

        foreach (Input::get('detail') as $detail) {
            $jobDetail = new JobDetailModel;
            $jobDetail->job_id = $jobId;

            $details = array();
            $details = explode("|||", $detail);

            $jobDetail->question_id = $details[0];
            if ($details[1] != '') {
                $jobDetail->answer_id = $details[1];
            }
            $jobDetail->value = $details[2];
            $jobDetail->save();
        }

        if (Input::hasFile('attachment')) {
            $jobAttachment = new JobAttachmentModel;
            $jobAttachment->job_id = $jobId;
            $filename = str_random(24).".".Input::file('attachment')->getClientOriginalExtension();
            Input::file('attachment')->move(ABS_ATTACHMENT_PATH, $filename);
            $jobAttachment->sys_name = $filename;
            $jobAttachment->org_name = Input::file('attachment')->getClientOriginalName();
            $jobAttachment->save();
        }
        
        if (Session::has('user_id')) {
            Queue::push('\Inquirymall\Queue\SendEmail', ['job' => $job] );
            $alert['msg'] = trans('job.success_post');
            $alert['type'] = 'success';
        
            return Redirect::route('job.detail', $job->slug )->with('alert', $alert);
        } else {
            $alert['msg'] = trans('job.signup_to_post');
            $alert['type'] = 'danger';
        
            return Redirect::route('user.signup')->with('alert', $alert);
        }
    }

    public function doBid() {
        $jobId = Input::get('job_id');
        $description = Input::get('description');
        $price = Input::get('price');
        $job = JobModel::find($jobId);

        $result = $this->fnDoBid($jobId, $description, $price);
        $alert['msg'] = $result['msg'];
        $alert['type'] = ($result['result'] == 'success') ? 'success' : 'danger';

        return Redirect::route('job.detail', $job->slug )->with('alert', $alert);
    }

    public function detail($slug) {
        $param['pageNo'] = 0;
        $job = JobModel::findBySlug($slug);
        if (!$job) {
            return Redirect::route('home.index');
        }        
        $job->count_view = $job->count_view + 1;
        $job->save();

        $param['job'] = $job;
        $param['count_hire'] = DB::table('bid')
                                ->join('job', 'bid.job_id', '=', 'job.id')
                                ->where('job.user_id', '=', $job->user_id)
                                ->where('bid.status', '=', 'HIRED')
                                ->where('job.status', '=', 'PROGRESS')                                
                                ->count();

        $param['sum_spent'] = DB::table('bid')
                                ->join('job', 'bid.job_id', '=', 'job.id')
                                ->where('job.user_id', '=', $job->user_id)
                                ->where('bid.status', '=', 'HIRED')
                                ->where('job.status', '=', 'PROGRESS')
                                ->sum('price');
        $jobId = $job->id;
        
        if (Session::get('user_id')) {
            $userId = Session::get('user_id');
            if ($job->status == 'OPEN') {
                if ($userId == $job->user_id) {
                    $param['type'] = 3;
                } else {
                    $count_bid = BidModel::where('job_id', $jobId)->where('user_id', $userId)->get()->count();
                    if ($count_bid > 0) {
                        $param['type'] = 2;
                    } else {
                        $param['user'] = UserModel::find(Session::get('user_id'));
                        $param['type'] = 1;
                    }
                }
            } elseif ($job->status == 'PROGRESS') {
                if ($userId == $job->user_id) {
                    $param['type'] = 4;
                } else {
                    $param['type'] = 0;
                }
            } else {
                $bid = BidModel::where('job_id', $jobId)->where('status', 'FINISHED')->firstOrFail();
                if ($bid->count() > 0) {
                    if ($job->user_id == $userId) {
                        $count_rate = RateModel::where('rater_id', $userId)->where('rated_id', $bid->user_id)->get()->count();
                        if ($count_rate > 0) {
                            $param['type'] = 6;
                        } else {
                            $param['type'] = 5;
                        }
                    } elseif ($bid->user_id == $userId) {
                        $count_rate = RateModel::where('rater_id', $userId)->where('rated_id', $job->user_id)->get()->count();
                        if ($count_rate > 0) {
                            $param['type'] = 6;
                        } else {
                            $param['type'] = 5;
                        }
                    } else {
                        $param['type'] = 0;
                    }
                } else {
                    $param['type'] = 0;
                }
            }
        } else {
            $param['type'] = 10;
        }

        if ($alert = Session::get('alert')) {
            $param['alert'] = $alert;
        }
        return View::make('frontend.job.detail')->with($param);
    }
    
    public function complete($id) {
        $job = JobModel::find($id);
        $job->status = 'COMPLETE';
        $job->save();
        
        $bid = BidModel::where('job_id', $job->id)->where('status', 'HIRED')->firstOrFail();
        $bid->status = 'FINISHED';
        $bid->save();
        
        $feed = new FeedModel;
        $feed->user_id = $bid->user_id;
        $feed->job_id = $id;
        $feed->type = 'FD04';
        $feed->save();
        
        $alert['msg'] = trans('job.success_complete');
        $alert['type'] = 'success';
        return Redirect::route('job.detail', $job->slug )->with('alert', $alert);
    }
    
    public function giveFeedback() {
        $jobId = Input::get('job_id');
        $description = Input::get('description');
        $score = Input::get('score');
        $job = JobModel::find($jobId);
        $bid = BidModel::where('job_id', $jobId)->where('status', 'FINISHED')->firstOrFail();
        
        $rate = new RateModel;
        if ($job->user_id == Session::get('user_id')) {
            $rate->rater_id = $job->user_id;
            $rate->rated_id = $bid->user_id;
            $rate->is_creator = TRUE;
        } else {
            $rate->rater_id = $bid->user_id;
            $rate->rated_id = $job->user_id;
            $rate->is_creator = FALSE;
        }
        $rate->job_id = $jobId;
        $rate->score = $score;
        $rate->description = $description;        
        $rate->save();
        
        $alert['msg'] = trans('job.success_feedback_provide');
        $alert['type'] = 'success';
        return Redirect::route('job.detail', $job->slug )->with('alert', $alert);        
    }

    public function hire() {
        $bidId = Input::get('bid_id');
        $bid = BidModel::find($bidId);
        $bid->status = 'HIRED';
        $bid->save();

        $job = JobModel::find($bid->job_id);
        $job->status = 'PROGRESS';
        $job->save();

        $alert['msg'] = trans('job.success_hire');
        $alert['type'] = 'success';

        $feed = new FeedModel;        
        $feed->user_id = $bid->user_id;
        $feed->job_id = $bid->job_id;
        $feed->type = 'FD01';
        $feed->save();
        
        $user = UserModel::find($bid->user_id);
        
        // Email History
        $email = EmailModel::where('code', 'ET04')->firstOrFail();
        $data = ['body' => str_replace('{job_link}', URL::route('job.detail', $job->slug), $email->body)];

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
        

        return Redirect::route('job.detail', $job->slug )->with('alert', $alert);
    }

    public function center($type = 'active') {
        if (!Session::has('user_id')) {
            return Redirect::route('home.index');
        }
                
        $param['pageNo'] = 4;
        $param['subPageNo'] = 12;

        if ($type == 'active') {
            $user = UserModel::find(Session::get('user_id'));
            $bids= $user->bids;
            $jobIds[] = 0;
            foreach ($bids as $bid) {
                $jobIds[] = $bid->job_id;
            }
            $param['results'] = JobModel::where('status', 'OPEN')
                                ->whereNotIn('id', $jobIds)
                                ->whereNotNull('user_id')
                                ->orderBy('id', 'DESC')->paginate(PAGINATION_SIZE);
        } elseif ($type == 'awarded') {
            $bids = BidModel::where('status', 'HIRED')->where('user_id', Session::get('user_id'))->orderBy('id', 'DESC')->paginate(PAGINATION_SIZE);
            $param['results'] = $bids;
        } elseif ($type == 'bidded') {
            $bids = BidModel::where('status', 'WAITING')->where('user_id', Session::get('user_id'))->orderBy('id', 'DESC')->paginate(PAGINATION_SIZE);
            $param['results'] = $bids;
        } else {
            $jobs = JobModel::where('status', 'COMPLETE')->where('user_id', Session::get('user_id'))->orderBy('id', 'DESC')->paginate(PAGINATION_SIZE);
            $param['results'] = $jobs;
        }
        $param['type'] = $type;
        return View::make('frontend.job.center')->with($param);
    }

    public function asyncQuestions() {
        $categoryId = Input::get('category_id');
        $category = CategoryModel::find($categoryId);

        $result = array();
        $i = 0;

        foreach ($category->questions as $question) {
            if ($question->is_selectable) {
                $answers = $question->answers;
                $result[$i]['answers'] = $answers;
            }
            $result[$i]['id'] = $question->id;
            $result[$i]['name'] = $question->name;
            $result[$i]['is_selectable'] = $question->is_selectable;
            $result[$i]['is_multiple'] = $question->is_multiple;
            $result[$i]['is_notable'] = $question->is_notable;
            $result[$i]['is_optional'] = $question->is_optional;
            $i++;
        }
        return Response::json($result);
    }

    public function asyncDoBid() {
        $jobId = Input::get('job_id');
        $description = Input::get('description');
        $price = Input::get('price');
        $result = $this->fnDoBid($jobId, $description, $price);
        return Response::json($result);
    }

    public function fnDoBid($jobId, $description, $price) {
        if (Session::has('user_id')) {
            
            $connectionRequire = ConnectionRequireModel::where('min', '<=', $price)
                                                        ->where('max', '>=', $price)
                                                        ->get();
            if (count($connectionRequire) > 0){
                $connections = $connectionRequire[0]->count_connection;
            } else {
                $connections = 1;
            }
            
            
            $userId = Session::get('user_id');

            $job = JobModel::find($jobId);
            $user = UserModel::find($userId);
            
            if ($job->user_id == $userId) {
                $result['msg'] = trans('job.error_not_bid_yours');
                $result['result'] = 'failed';
            } elseif ($job->status != 'OPEN') {
                $result['msg'] = trans('job.error_started');
                $result['result'] = 'failed';
            } else {
                if ($user->count_connection >= $connections) {
                    
                } else {
                    $countConnection = AUTO_CONNECTION;
                    $user->count_connection = $user->count_connection + $countConnection;
                    $user->save();
                    
                    $buy = new BuyModel;
                    $buy->user_id = $user->id;
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
                    
                }
                
                $count_bid = BidModel::where('job_id', $jobId)->where('user_id', $userId)->count();
                
                if ($count_bid > 0) {
                    $bid = BidModel::where('job_id', $jobId)->where('user_id', $userId)->firstOrFail();
                    $result['msg'] = trans('job.success_bid_update');
                    $result['result'] = 'success';
                } else {
                    $bid = new BidModel;
                    $result['msg'] = trans('job.success_bid_done');
                    $result['result'] = 'success';
                }
                
                $bid->job_id = $jobId;
                $bid->user_id = $userId;
                $bid->price = $price;
                $bid->description = $description;
                $bid->status = 'WAITING';
                $bid->save();
                
                // Notification
                $feed = new FeedModel;
                $feed->user_id = $job->user_id;
                $feed->job_id = $jobId;
                $feed->type = 'FD03';
                $feed->save();
                
                $user->count_connection = $user->count_connection - $connections;
                $user->save();
                
                // Email History
                $email = EmailModel::where('code', 'ET02')->firstOrFail();
                $data = ['body' => str_replace('{user_link}', URL::route('user.detail', $user->slug), $email->body)];
                
                $user = UserModel::find($bid->job->user_id);
                $info = [ 'reply_name'  => ($email->reply_name == '') ? REPLY_NAME : $email->reply_name,
                          'reply_email' => ($email->reply_email == '') ? REPLY_EMAIL : $email->reply_email,
                          'email'       => $user->email,
                          'name'        => $user->name,
                          'subject'     => $email->subject,
                        ];
                
                try {
                    $result1 = Mail::send('email.blank', $data, function($message) use ($info) {
                        $message->from($info['reply_email'], $info['reply_name']);
                        $message->to($info['email'], $info['name'])
                        ->subject($info['subject']);
                    });
                } catch(Swift_SwiftException $e) {
                
                }
                
/*                 $result['msg'] = trans('job.error_not_enough_bids');
                 $result['result'] = 'danger'; */
            }

        } else {
            $result['msg'] = trans('job.error_login_bid');
            $result['result'] = 'failed';
        }
        return $result;
    }
}
