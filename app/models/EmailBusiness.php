<?php

use Illuminate\Database\Eloquent\Model as Eloquent;

class EmailBusiness extends Eloquent {
    
    protected $table = 'email_business';
    
    public function business() {
        return $this->belongsTo('Business', 'business_id');
    }    
    
}
