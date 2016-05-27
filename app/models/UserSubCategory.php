<?php

use Illuminate\Database\Eloquent\Model as Eloquent;

class UserSubCategory extends Eloquent {
    
    protected $table = 'user_sub_category';
    
    public function subCategory() {
        return $this->belongsTo('SubCategory', 'sub_category_id');
    }    
    
}
