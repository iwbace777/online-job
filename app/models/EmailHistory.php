<?php

use Illuminate\Database\Eloquent\Model as Eloquent;

class EmailHistory extends Eloquent {
    
    protected $table = 'email_history';
    
    public function user() {
        return $this->belongsTo('User', 'user_id');
    }    
    
}
