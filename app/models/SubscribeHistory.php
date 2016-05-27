<?php

use Illuminate\Database\Eloquent\Model as Eloquent;

class SubscribeHistory extends Eloquent {
    
    protected $table = 'subscribe_history';
    
    public function user() {
        return $this->belongsTo('User', 'user_id');
    }
    
    public function plan() {
        return $this->belongsTo('Plan', 'plan_id');
    }    
    
}
