<?php namespace Batch;

use Illuminate\Routing\Controllers\Controller;
use View, Input, Redirect, Session, Validator, Log;
use EmailHistory as EmailHistoryModel;

class EmailHistoryController extends \BaseController {
        
    public function checkRead($period = 3) {
        Log::info("Period : $period");
        $emailHistories = EmailHistoryModel::whereRaw("DATE_ADD(NOW(), INTERVAL -$period HOUR) >= updated_at")
                                    ->whereRaw("DATE_ADD(NOW(), INTERVAL -24 HOUR) <= updated_at")
                                    ->where('is_read', FALSE)
                                    ->get();
        
        foreach ($emailHistories as $emailHistory) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://api.sendgrid.com/api/stats.get.json');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER , false);

            $data = [
                    'api_user'   => SENDGRID_USER,
                    'api_key'    => SENDGRID_PASS,
                    'category'   => $emailHistory->token,
                    'aggregate'  => 1
            ];
            
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            $result = curl_exec($ch);
            curl_close($ch);

            $statistics = json_decode( $result, true );
            
            if( !isset($statistics["error"]) ){
                $count_open = 0;
                $count_click = 0;
                foreach ($statistics as $stat) {
                    $count_open += isset($stat['opens']) ? $stat['opens'] : 0;
                    $count_click += isset($stat['clicks']) ? $stat['clicks'] : 0;
                }
                if ($count_open > 0 || $count_click > 0) {
                    $emailHistory->is_read = TRUE;
                    $emailHistory->save();
                }
            }            
        }
    }
}
