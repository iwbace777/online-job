<?php

use Illuminate\Database\Eloquent\Model as Eloquent;

class Buy extends Eloquent {
    
    protected $table = 'buy';
    
    public function user() {
        return $this->belongsTo('User', 'user_id');
    }
    
    
}
