<?php

use Illuminate\Database\Eloquent\Model as Eloquent;

class Feed extends Eloquent {
    
    protected $table = 'feed';
    
    public function job() {
        return $this->belongsTo('\Inquirymall\Models\Job', 'job_id');
    }

    public function user() {
        return $this->belongsTo('User', 'user_id');
    }    
    
}
