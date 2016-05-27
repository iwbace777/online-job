<?php

use Illuminate\Database\Eloquent\Model as Eloquent;

class Bid extends Eloquent {
    
    protected $table = 'bid';
    
    public function job() {
        return $this->belongsTo('\Inquirymall\Models\Job', 'job_id');
    }

    public function user() {
        return $this->belongsTo('User', 'user_id');
    }    
    
}
