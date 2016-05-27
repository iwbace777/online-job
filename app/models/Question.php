<?php

use Illuminate\Database\Eloquent\Model as Eloquent;

class Question extends Eloquent {
    
    protected $table = 'question';
    
    public function subCategory() {
        return $this->belongsTo('SubCategory', 'sub_category_id');
    }
    
    public function answers() {
        return $this->hasMany('Answer', 'question_id')->orderBy('order', 'ASC')->orderBy('id', 'ASC');;
    }    
    
}
