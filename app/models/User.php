<?php

use Illuminate\Database\Eloquent\Model as Eloquent;
use Cviebrock\EloquentSluggable\SluggableInterface;
use Cviebrock\EloquentSluggable\SluggableTrait;

class User extends Eloquent implements SluggableInterface {
    use SluggableTrait;
    
    protected $table = 'user';
    
    protected $sluggable = array(
        'build_from' => 'name',
        'save_to'    => 'slug',
    );    
    
    public function city() {
        return $this->belongsTo('City', 'city_id');
    }
    
    public function jobs() {
        return $this->hasMany('\Inquirymall\Models\Job', 'user_id');
    }

    public function bids() {
        return $this->hasMany('Bid', 'user_id');
    }
    
    public function subCategories() {
        return $this->hasMany('UserSubCategory', 'user_id');
    }    

    public function notes() {
        return $this->hasMany('UserNote', 'user_id');
    }

    public function rates() {
        return $this->hasMany('Rate', 'rated_id')->orderBy('id', 'DESC');
    }

    public function newletters() {
        return $this->hasMany('EmailHistory', 'user_id');
    }
    
    public function transactions() {
        return $this->hasMany('\Inquirymall\Models\Transaction', 'user_id');
    }
    
    public function subscribes() {
        return $this->hasMany('Subscribe', 'user_id');
    }
    
    public function buys() {
        $tblBuy = with(new Buy)->getTable();
        return $this->hasMany('Buy', 'user_id')->where($tblBuy.'.is_paid', TRUE);
    }
    
    public function search($keyword, $type, $categoryId, $cityId) {
        $sqlUser = "
                    SELECT t1.*, t2.name AS city_name, IFNULL(t3.score, 0) AS score, IFNULL(t3.cnt, 0) AS cnt
                      FROM (
                    	SELECT t1.id, t1.name, t1.slug, t1.email, t1.address, t1.phone, t1.city_id, t1.photo, t1.description, t1.hourly_rate, t1.is_business, 0 AS is_subscriber, t2.cat_name, t2.cat_name2, t2.ids
                    	  FROM im_user t1,
                    		(
                    		SELECT t1.user_id, GROUP_CONCAT(NAME) AS cat_name, GROUP_CONCAT(name2) AS cat_name2, GROUP_CONCAT(t2.id) AS ids
                    		  FROM (SELECT user_id, category_id
                    			  FROM im_user_sub_category
                    			 GROUP BY user_id, category_id) t1, im_category t2
                    		 WHERE t1.category_id = t2.id
                    		 GROUP BY t1.user_id
                    		) t2
                    	 WHERE t1.id = t2.user_id
                    	   AND t1.is_active
                    	) AS t1
                      LEFT JOIN im_city AS t2 ON t1.city_id = t2.id
                      LEFT JOIN
                    	(
                    	SELECT rated_id AS user_id, AVG(score) AS score, COUNT(*) AS cnt
                    	  FROM im_rate
                    	 GROUP BY rated_id
                    	) AS t3 ON t1.id = t3.user_id";
    
        $sqlBusiness = "
                    SELECT t1.*, t2.name AS city_name, 0 AS score, 0 AS cnt
                      FROM (
                    	SELECT t1.id, t1.name, '' as slug, t1.email, t1.address, t1.phone, t1.city_id, '".DEFAULT_PHOTO."' AS photo, t1.description, NULL as hourly_rate, 1 AS is_business, 1 AS is_subscriber, t2.cat_name, t2.cat_name2, t2.ids
                    	  FROM im_business t1,
                    		(
                    		SELECT t1.business_id, GROUP_CONCAT(NAME) AS cat_name, GROUP_CONCAT(name2) AS cat_name2, GROUP_CONCAT(t2.id) AS ids
                    		  FROM (SELECT business_id, category_id
                    			  FROM im_business_sub_category
                    			 GROUP BY business_id, category_id) t1, im_category t2
                    		 WHERE t1.category_id = t2.id
                    		 GROUP BY t1.business_id
                    		) t2
                    	 WHERE t1.id = t2.business_id
                    	   AND t1.is_subscriber
                    	) AS t1
                      LEFT JOIN im_city AS t2 ON t1.city_id = t2.id";
    
        $sql = "SELECT * FROM ($sqlUser union all $sqlBusiness) AS t1 WHERE 1 = 1";
    
        if ($keyword != '') {
            $sql .= " AND name LIKE '%".$keyword."%'";
        }
    
        if ($type != '') {
            $sql .= " AND is_business = $type";
        }
    
        if ($cityId != '') {
            $sql .= " AND city_id = $cityId";
        }
    
        if ($categoryId != '') {
            $sql .= " AND $categoryId IN (ids)";
        }
        $sql .= " ORDER BY is_subscriber, score DESC, name";
        return DB::select(DB::raw($sql));
    }

}
