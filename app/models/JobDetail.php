<?php

use Illuminate\Database\Eloquent\Model as Eloquent;

class JobDetail extends Eloquent {
    
    protected $table = 'job_detail';
    
    public function question() {
        return $this->belongsTo('Question', 'question_id');
    }
    
    public function answer() {
        return $this->belongsTo('Answer', 'answer_id');
    }
    
}
