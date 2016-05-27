<?php namespace Inquirymall\Queue;

use URL, SendGrid, Log;
use Email as EmailModel;
use UserSubCategory as UserSubCategoryModel;
use User as UserModel;
use Feed as FeedModel;
use EmailHistory as EmailHistoryModel;
use BusinessSubCategory as BusinessSubCategoryModel;
use Business as BusinessModel;
use EmailBusiness as EmailBusinessModel;
use \Inquirymall\Models\Setting as SettingModel;

class SendEmail {

    public function fire($j, $data)
    {
        $setting = SettingModel::where('code', 'CD11')->firstOrFail();
        if ($setting->value == "YES") {
            $job = $data['job'];
            $userId = $job['user_id'];
            
            // Email History
            $email = EmailModel::where('code', 'ET01')->firstOrFail();
            $data = ['body' => str_replace('{job_link}', URL::route('job.detail', $job['slug']), $email->body)];
            
            $userSubCategories = UserSubCategoryModel::where('sub_category_id', $job['sub_category_id'])->get();
            foreach ($userSubCategories as $userSubCategory) {
                $user = UserModel::find($userSubCategory->user_id);
                if (($userSubCategory->user_id != $userId && $job['city_id'] == $user->city_id && $job['district_id'] == NULL) 
                 || ($userSubCategory->user_id != $userId && $job['city_id'] == $user->city_id && $job['district_id'] != NULL && $job['district_id'] == $user->district_id)) {
                    // Notification
                    $feed = new FeedModel;
                    $feed->user_id = $userSubCategory->user_id;
                    $feed->job_id = $job['id'];
                    $feed->type = 'FD02';
                    $feed->save();
            
                    $sendgrid = new SendGrid(SENDGRID_USER, SENDGRID_PASS, array("turn_off_ssl_verification" => true));
                    $mail = new SendGrid\Email();
            
                    $receivers = array();
                    
                    if ($user->email != '') {
                        $receivers[] = $user->email;
                    }
            
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
            
                    $token = str_random(32);
                    $emailHistory = new EmailHistoryModel;
                    $emailHistory->email_id = $email->id;
                    $emailHistory->job_id = $job['id'];
                    $emailHistory->user_id = $user->id;
                    $emailHistory->token = $token;
                    $emailHistory->is_read = FALSE;
                    $emailHistory->save();
            
                    $mail->setTos($receivers)
                        ->setFrom(($email->reply_email == '') ? REPLY_EMAIL : $email->reply_email)
                        ->setFromName(($email->reply_name == '') ? REPLY_NAME : $email->reply_name)
                        ->setSubject($email->subject)
                        ->setHtml($data['body'])
                        ->addCategory($token);
                    $sendgrid->send($mail);
                }
            }
            
            $email = EmailModel::where('code', 'ET14')->firstOrFail();
            $data = ['body' => str_replace('{job_link}', URL::route('job.detail', $job['slug']), $email->body)];            
            
            $businessSubCategories = BusinessSubCategoryModel::where('sub_category_id', $job['sub_category_id'])->get();
            foreach ($businessSubCategories as $businessSubCategory) {
                $business = BusinessModel::find($businessSubCategory->business_id);
                if (($business->is_subscriber && $job['city_id'] == $business->city_id && $job['district_id'] == NULL)
                    || ($business->is_subscriber && $job['city_id'] == $business->city_id && $job['district_id'] != NULL && $job['district_id'] == $business->district_id)) {
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
            
                    $token = str_random(32);
                    $mail->setTos($receivers)
                        ->setFrom(($email->reply_email == '') ? REPLY_EMAIL : $email->reply_email)
                        ->setFromName(($email->reply_name == '') ? REPLY_NAME : $email->reply_name)
                        ->setSubject($email->subject)
                        ->setHtml($data['body'])
                        ->addCategory($token);
                    $sendgrid->send($mail);
            
                    $emailBusiness = new EmailBusinessModel;
                    $emailBusiness->email_id = $email->id;
                    $emailBusiness->job_id = $job['id'];
                    $emailBusiness->business_id = $business->id;
                    $emailBusiness->token = $token;
                    $emailBusiness->is_read = FALSE;
                    $emailBusiness->save();
                }
            }
        }
        
        $j->delete();
    }

} 
