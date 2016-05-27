<?php

use Illuminate\Database\Eloquent\Model as Eloquent;

class BusinessSubCategory extends Eloquent {
    
    protected $table = 'business_sub_category';
    
    public function subCategory() {
        return $this->belongsTo('SubCategory', 'sub_category_id');
    }    
    
}
