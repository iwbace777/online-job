<?php namespace Inquirymall\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Cviebrock\EloquentSluggable\SluggableInterface;
use Cviebrock\EloquentSluggable\SluggableTrait;

class Job extends Eloquent implements SluggableInterface {
    
    use SluggableTrait;
    
    protected $sluggable = array(
                    'build_from' => 'name',
                    'save_to'    => 'slug',
    );    
    
    protected $table = 'job';
    
    public function user() {
        return $this->belongsTo('User', 'user_id');
    }
    
    public function category() {
        return $this->belongsTo('Category', 'category_id');
    }
    
    public function subCategory() {
        return $this->belongsTo('SubCategory', 'sub_category_id');
    }    
    
    public function city() {
        return $this->belongsTo('City', 'city_id');
    }

    public function district() {
        return $this->belongsTo('District', 'district_id');
    }    

    public function details() {
        return $this->hasMany('JobDetail', 'job_id');
    }
    
    public function attachments() {
        return $this->hasMany('JobAttachment', 'job_id');
    }

    public function bids() {
        return $this->hasMany('Bid', 'job_id');
    }
    
    public function userBids() {
        $tblBid = with(new \Bid)->getTable();
        return $this->hasMany('Bid', 'job_id')->where($tblBid.'.is_admin', FALSE);        
    }
    
    public function emailHistories() {
        return $this->hasMany('EmailHistory', 'job_id');
    }
    
    public function emailBusinesses() {
        return $this->hasMany('EmailBusiness', 'job_id');
    }    
}
