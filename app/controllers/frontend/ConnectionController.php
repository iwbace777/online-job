<?php namespace Frontend;

use Illuminate\Routing\Controllers\Controller;
use View, Input, Redirect, Session, Validator, Response, Log, Mail, Stripe;
use Package as PackageModel, \Inquirymall\Models\Transaction as TransactionModel, User as UserModel;
use Plan as PlanModel, Subscribe as SubscribeModel, SubscribeHistory as SubscribeHistoryModel;
use Buy as BuyModel;

class ConnectionController extends \BaseController {

    public function purchase() {
        if (!Session::has('user_id')) {
            return Redirect::route('home.index');
        }
        
        $param['pageNo'] = 4;
        $param['subPageNo'] = 16;
        
        $param['packages'] = PackageModel::all();
        $param['user'] = UserModel::find(Session::get('user_id'));
        
        $param['is_subscribed'] = (SubscribeModel::where('user_id', Session::get('user_id'))->count() > 0) ? TRUE : FALSE;
        $param['plans'] = PlanModel::all();        
                
        if ($alert = Session::get('alert')) {
            $param['alert'] = $alert;
        }
        return View::make('frontend.connection.purchase')->with($param);
    }
    
    public function purchaseIPN() {
        $req = 'cmd=_notify-validate';
        
        foreach ($_POST as $key => $value) {
            $value = urlencode(stripslashes($value));
            $req .= "&$key=$value";
        }
        
        //post back to PayPal system to validate
        $header = "POST /cgi-bin/webscr HTTP/1.1\r\n";
        $header .= "Content-Type: application/x-www-form-urlencoded\r\n";
        $header .= "Host: ".PAYPAL_SERVER."\r\n";
        $header .= "Connection: close\r\n";
        $header .= "Content-Length: " . strlen($req) . "\r\n\r\n";
        $fp = fsockopen ('ssl://'.PAYPAL_SERVER, 443, $errno, $errstr, 30);
        if ($fp) {
            fputs ($fp, $header . $req);
            while (!feof($fp)) {
                $res = fgets ($fp, 1024);
                $res = trim($res);
                
                if (strcmp($res, "VERIFIED") == 0) 
                {
                    //insert order into database
                    $invoice = Input::get('invoice');
                    
                    $transaction = TransactionModel::where('invoice', $invoice)->firstOrFail();
                    $transaction->txn_id = Input::get('txn_id');
                    $transaction->amount = Input::get('mc_gross');
                    $transaction->is_paid = TRUE;
                    $transaction->data = var_export($_POST, true);
                    $transaction->save();
                    
                    $package = PackageModel::find($transaction->package_id);
                    
                    $user = UserModel::find($transaction->user_id);
                    $user->count_connection = $user->count_connection + $package->count;
                    $user->save();
                    
                    $total = $transaction->amount;
                    $amount = round($total - $total / (1 + VAT_PERCENT * 0.01), 2);
                    $tax = round($total - $amount, 2);
                    
                    
                    $package = PackageModel::find($transaction->package_id);
                    
                    $email = EmailModel::where('code', 'ET08')->firstOrFail();
                    $body = $email->body;
                    $body = str_replace('{invoiceNo}', $transaction->invoice, $body);
                    $body = str_replace('{name}', $package->name, $body);
                    $body = str_replace('{amount}', $amount, $body);
                    $body = str_replace('{tax}', $tax, $body);
                    $body = str_replace('{total}', $total, $body);
                    $body = str_replace('{date}', $transaction->updated_at, $body);
                    
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
                
                if (strcmp ($res, "INVALID") == 0) {
                    
                }
            }
            fclose($fp);
        }
    }
    
    public function purchaseSuccess() {
        $param['pageNo'] = 4;
        $param['subPageNo'] = 16;
        return View::make('frontend.connection.success')->with($param);        
    }
    
    public function purchaseFailed() {
        $param['pageNo'] = 4;
        $param['subPageNo'] = 16;
        return View::make('frontend.connection.failed')->with($param);
    }    
    
    public function asyncPurchase() {
        $transaction = new TransactionModel;
        $transaction->user_id = Session::get('user_id');
        $transaction->package_id = Input::get('package_id');
        $transaction->invoice = "PURCHASE-".strtoupper(str_random(6))."-".strtoupper(str_random(3));
        $transaction->is_paid = FALSE;
        $transaction->ip = $_SERVER['REMOTE_ADDR'];
        $transaction->save();        
        $package = PackageModel::find($transaction->package_id);
        return Response::json(['result' => 'success', 'msg' => '', 'invoice' => $transaction->invoice, 'amount' => $package->price, ]);        
    }
    
    public function doBuy() {
        $rules = ['count'      => 'numeric|required'];
    
        $validator = Validator::make(Input::all(), $rules);
    
        if ($validator->fails()) {
            return Redirect::back()
                    ->withErrors($validator)
                    ->withInput();
        } else {
            $buy = new BuyModel;
            $buy->user_id = Session::get('user_id');
            $buy->count = Input::get('count');
            $buy->description = '';
            $buy->is_paid = FALSE;
            $buy->is_sent_invoice = FALSE;
            $buy->invoice_no = strtoupper(str_random(6));
            $buy->save();
    
            $buy->due_at = date("Y-m-d", strtotime("15 days", strtotime($buy->created_at)));
            $buy->save();
    
    
            $alert['msg'] = trans('connection.msg_transfer_via_bank', ['amount' => Input::get('count') * CONNECTION_PRICE, ]);
            $alert['type'] = 'success';
    
            return Redirect::route('connection.purchase')->with('alert', $alert);
        }
    
    }    
    
    public function webhook() {
        $input = @file_get_contents("php://input");
        $result = json_decode($input);
        
        if ($result->type == 'customer.subscription.created') {
            $subscriptionCode = $result->data->object->id;
            $customerCode = $result->data->object->customer;
            $subscribe = SubscribeModel::where('customer_code', $customerCode)->get();
            if (count($subscribe) > 0) {
                $subscribe[0]->subscription_code = $subscriptionCode;
                $subscribe[0]->save();
                
                $plan = PlanModel::find($subscribe[0]->plan_id);
                
                $user = UserModel::find($subscribe[0]->user_id);
                $user->count_connection = $user->count_connection + $plan->count;
                $user->save();
                
                $subscribeHistory = new SubscribeHistoryModel;
                $subscribeHistory->user_id = $subscribe[0]->user_id;
                $subscribeHistory->plan_id = $subscribe[0]->plan_id;
                $subscribeHistory->invoice = "SUBSCRIBE-".strtoupper(str_random(6))."-".strtoupper(str_random(3));
                $subscribeHistory->amount = $subscribe[0]->plan->price;
                $subscribeHistory->customer_code = $subscribe[0]->customer_code;
                $subscribeHistory->subscription_code = $subscribe[0]->subscription_code;
                $subscribeHistory->save();
                
                $total = $plan->price;
                $amount = round($total - $total / (1 + VAT_PERCENT * 0.01), 2);
                $tax = round($total - $amount, 2);
                
                $email = EmailModel::where('code', 'ET08')->firstOrFail();
                $body = $email->body;
                $body = str_replace('{invoiceNo}', $subscribeHistory->invoice, $body);
                $body = str_replace('{name}', $plan->name, $body);
                $body = str_replace('{amount}', $amount, $body);
                $body = str_replace('{tax}', $tax, $body);
                $body = str_replace('{total}', $total, $body);
                $body = str_replace('{date}', $subscribe[0]->updated_at, $body);
                
                $data = ['body' => $body];
                                
                $info = [ 'reply_name'  => INVOICE_NAME,
                          'reply_email' => INVOICE_EMAIL,
                          'email'       => $user->email,
                          'name'        => $user->name,
                          'subject'     => SITE_NAME." Invoice",
                        ];
                
                Mail::send('email.blank', $data, function($message) use ($info) {
                    $message->from($info['reply_email'], $info['reply_name']);
                    $message->to($info['email'], $info['name'])
                            ->subject($info['subject']);
                });
            }
        } elseif ($result->type == 'customer.subscription.deleted') {
            // Log::info('RESULT : '.var_export($result, true));
        }
        
        http_response_code(200);
    }
    
    public function createSubscribe($planCode) {
        $stripeToken = Input::get('stripeToken');
        $stripeTokenType = Input::get('stripeTokenType');
        $stripeEmail = Input::get('stripeEmail');
        
        \Stripe\Stripe::setApiKey(STRIPE_SECRET_KEY);
        
        $customer = \Stripe\Customer::create(array(
            "source" => $stripeToken,
            "plan" => $planCode,
            "email" => $stripeEmail
        ));
        
        $plan = PlanModel::where('plan_code', $planCode)->firstOrFail();
        
        if (SubscribeModel::where('user_id', Session::get('user_id'))->count() > 0) {
            $subscribe = SubscribeModel::where('user_id', Session::get('user_id'))->firstOrFail();
        } else {
            $subscribe = new SubscribeModel;
        }
        $subscribe->user_id = Session::get('user_id');
        $subscribe->plan_id = $plan->id;
        $subscribe->customer_code = $customer->id;
        $subscribe->save();
        
        return Redirect::route('connection.purchase');
    }
    
    public function cancelSubscribe() {
        $subscribe = SubscribeModel::where('user_id', Session::get('user_id'))->firstOrFail();
        
        \Stripe\Stripe::setApiKey(STRIPE_SECRET_KEY);
        
        $customer = \Stripe\Customer::retrieve($subscribe->customer_code);
        $subscription = $customer->subscriptions->retrieve($subscribe->subscription_code);
        $subscription->cancel();
        
        $subscribe->delete();
        return Redirect::route('connection.purchase');
    }
}
