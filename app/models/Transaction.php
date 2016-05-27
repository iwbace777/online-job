<?php namespace Inquirymall\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Transaction extends Eloquent {
    
    protected $table = 'transaction';
    
    public function user() {
        return $this->belongsTo('User', 'user_id');
    }

    public function package() {
        return $this->belongsTo('Package', 'package_id');
    }    
    
}
