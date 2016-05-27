<?php namespace Inquirymall\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Message extends Eloquent {
    
    protected $table = 'message';
    
    public $is_sender;
    public $count_new;
    
    public function sender() {
        return $this->belongsTo('User', 'sender_id');
    }
    
    public function receiver() {
        return $this->belongsTo('User', 'receiver_id');
    }
    
    public function job() {
        return $this->belongsTo('\Inquirymall\Models\Job', 'job_id');
    }
        
    public function setSender($value) {
        $this->is_sender = $value;
    }
    
    public function setNew($value) {
        $this->count_new = $value;
    }    
}
