<?php

use Illuminate\Database\Eloquent\Model as Eloquent;

class District extends Eloquent {
    
    protected $table = 'district';
    
    public function city() {
        return $this->belongsTo('City', 'city_id');
    }

}
