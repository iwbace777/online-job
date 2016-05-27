<?php

use Illuminate\Database\Eloquent\Model as Eloquent;

class Business extends Eloquent {
    
    protected $table = 'business';

    public function subCategories() {
        return $this->hasMany('BusinessSubCategory', 'business_id');
    }

    public function city() {
        return $this->belongsTo('City', 'city_id');
    }
    
    public function scopeByCategory($query, $categoryId) {
        if ($categoryId == '') {
            return $query->select('*');
        } else {
            $tblBusinessSubCategory = with(new BusinessSubCategory)->getTable();
            
            $result = $query->select($this->table.'.*')
                            ->leftJoin($tblBusinessSubCategory, $tblBusinessSubCategory.'.business_id', '=', $this->table.'.id')
                            ->where($tblBusinessSubCategory.'.category_id', '=', $categoryId)
                            ->groupBy($this->table.'.id');
            return $result;
        }
    }
    
}
