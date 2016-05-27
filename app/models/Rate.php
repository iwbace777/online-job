<?php

use Illuminate\Database\Eloquent\Model as Eloquent;

class Rate extends Eloquent {
    
    protected $table = 'rate';
    
    public function rater() {
        return $this->belongsTo('User', 'rater_id');
    }
    
    public function rated() {
        return $this->belongsTo('User', 'rated_id');
    }
    
    public function job() {
        return $this->belongsTo('\Inquirymall\Models\Job', 'job_id');
    }
}
