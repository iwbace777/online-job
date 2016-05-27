<?php namespace Backend;

use Illuminate\Routing\Controllers\Controller;
use View, Input, Redirect, Session, Validator, DB;
use Inquirymall\Models\Transaction as TransactionModel;

class DashboardController extends \BaseController {
    
    public function __construct() {
        $this->beforeFilter(function(){
            if (!Session::has('admin_id')) {
                return Redirect::route('backend.auth.login');
            }
        });
    }

    public function index() {
        $param['pageNo'] = 1;        
        $startDate = Input::has('startDate') ? Input::get('startDate') : '';
        $endDate   = Input::has('endDate')   ? Input::get('endDate')   : '';        
        if ($startDate == '' || $endDate == '') {
            $endDate = date('Y-m-d');
            $startDate = substr($endDate, 0, 8)."01";
        }
        
        $param['averageUserPostProject'] = $this->averageUserPostProject($startDate, $endDate);
        $param['averageUserBidProject'] = $this->averageUserBidProject($startDate, $endDate);
        $param['revenue'] = $this->revenue($startDate, $endDate);
        $param['countUserRegister'] = $this->countUserRegister($startDate, $endDate);
        $param['countBid'] = $this->countBid($startDate, $endDate);
        $param['countPost'] = $this->countPost($startDate, $endDate);
        
        $param['totalRevenue'] = $this->totalRevenue($startDate, $endDate);
        $param['avgUserPostProject'] = $this->avgUserPostProject($startDate, $endDate);
        $param['avgUserBidProject'] = $this->avgUserBidProject($startDate, $endDate);
        $param['avgUserBidRate'] = $this->avgUserBidRate($startDate, $endDate);        

        $param['startDate'] = $startDate;
        $param['endDate'] = $endDate;        
        return View::make('backend.dashboard.index')->with($param);
    }

    public function averageUserPostProject($startDate, $endDate) {
        $prefix = DB::getTablePrefix();
        $sql = "SELECT DATE(created_at) dt, user_id, count(*) cnt
	              FROM ".$prefix."job
	             WHERE (DATE(created_at) BETWEEN '$startDate' AND '$endDate')
	             GROUP BY DATE(created_at), user_id";
         
        $sql = "SELECT dt, AVG(cnt) AS avg_cnt
                  FROM ($sql) t1
                 GROUP BY dt";
         
        $sqlPeriod = $this->allPeriod($startDate, $endDate);
        $sql = "SELECT t1.*, IFNULL(t2.avg_cnt, 0) AS avg_cnt, YEAR(t1.dt) as y, MONTH(t1.dt)-1 as m, DATE_FORMAT(t1.dt,'%e') as d
                  FROM ($sqlPeriod) t1
                  LEFT JOIN ($sql) t2
                    ON t1.dt = t2.dt
                 ORDER BY t1.dt ASC";
        return DB::select($sql);
    }
    
    public function averageUserBidProject($startDate, $endDate) {
        $prefix = DB::getTablePrefix();
        $sql = "SELECT DATE(created_at) dt, job_id, count(*) cnt
                  FROM ".$prefix."bid
                 WHERE (DATE(created_at) BETWEEN '$startDate' AND '$endDate')
                 GROUP BY DATE(created_at), job_id";
    
	    $sql = "SELECT dt, AVG(cnt) AS avg_cnt, YEAR(dt) as y, MONTH(dt)-1 as m, DATE_FORMAT(dt,'%e') as d
	              FROM ($sql) t1
    	         GROUP BY dt";
    	     
	    $sqlPeriod = $this->allPeriod($startDate, $endDate);
	    
	    $sql = "SELECT t1.*, IFNULL(t2.avg_cnt, 0) AS avg_cnt, YEAR(t1.dt) as y, MONTH(t1.dt)-1 as m, DATE_FORMAT(t1.dt,'%e') as d
    	          FROM ($sqlPeriod) t1
    	          LEFT JOIN ($sql) t2
    	            ON t1.dt = t2.dt
    	         ORDER BY t1.dt ASC";
	    return DB::select($sql);
    }
    
    public function revenue($startDate, $endDate) {
        $prefix = DB::getTablePrefix();
        $sql = "SELECT DATE(created_at) dt, sum(amount) amount
                  FROM ".$prefix."transaction
                 WHERE (DATE(created_at) BETWEEN '$startDate' AND '$endDate')
    	           AND is_paid
    	         GROUP BY DATE(created_at)";
	  
	    $sql = "SELECT t1.*, YEAR(dt) as y, MONTH(dt)-1 as m, DATE_FORMAT(dt,'%e') as d
	              FROM ($sql) t1";
	  
        $sqlPeriod = $this->allPeriod($startDate, $endDate);
        
        $sql = "SELECT t1.*, IFNULL(t2.amount, 0) AS amount, YEAR(t1.dt) as y, MONTH(t1.dt)-1 as m, DATE_FORMAT(t1.dt,'%e') as d
	              FROM ($sqlPeriod) t1
	              LEFT JOIN ($sql) t2
	                ON t1.dt = t2.dt
	             ORDER BY t1.dt ASC";
        return DB::select($sql);
    }
    
    public function countUserRegister($startDate, $endDate) {
        $prefix = DB::getTablePrefix();
        $sql = "SELECT DATE(created_at) dt, count(*) as amount
	              FROM ".$prefix."user
	             WHERE (DATE(created_at) BETWEEN '$startDate' AND '$endDate')
	             GROUP BY DATE(created_at)";
	  
	    $sql = "SELECT t1.*, YEAR(dt) as y, MONTH(dt)-1 as m, DATE_FORMAT(dt,'%e') as d
	              FROM ($sql) t1";
    	               
        $sqlPeriod = $this->allPeriod($startDate, $endDate);
        
        $sql = "SELECT t1.*, IFNULL(t2.amount, 0) AS amount, YEAR(t1.dt) as y, MONTH(t1.dt)-1 as m, DATE_FORMAT(t1.dt,'%e') as d
	              FROM ($sqlPeriod) t1
	              LEFT JOIN ($sql) t2
  	                ON t1.dt = t2.dt
	             ORDER BY t1.dt ASC";
        return DB::select($sql);
    }
    
    public function countBid($startDate, $endDate) {
        $prefix = DB::getTablePrefix();
        $sql = "SELECT DATE(created_at) dt, count(*) as amount
	              FROM ".$prefix."bid
	             WHERE (DATE(created_at) BETWEEN '$startDate' AND '$endDate')
	             GROUP BY DATE(created_at)";
    
	    $sql = "SELECT t1.*, YEAR(dt) as y, MONTH(dt)-1 as m, DATE_FORMAT(dt,'%e') as d
    	          FROM ($sql) t1";
	    
        $sqlPeriod = $this->allPeriod($startDate, $endDate);
        
	    $sql = "SELECT t1.*, IFNULL(t2.amount, 0) AS amount, YEAR(t1.dt) as y, MONTH(t1.dt)-1 as m, DATE_FORMAT(t1.dt,'%e') as d
    	          FROM ($sqlPeriod) t1
    	          LEFT JOIN ($sql) t2
    	            ON t1.dt = t2.dt
    	         ORDER BY t1.dt ASC";
    	return DB::select($sql);
    }
    
    public function countPost($startDate, $endDate) {
        $prefix = DB::getTablePrefix();
        $sql = "SELECT DATE(created_at) dt, count(*) as amount
    	          FROM ".$prefix."job
	             WHERE (DATE(created_at) BETWEEN '$startDate' AND '$endDate')
	             GROUP BY DATE(created_at)";
    
	    $sql = "SELECT t1.*, YEAR(dt) as y, MONTH(dt)-1 as m, DATE_FORMAT(dt,'%e') as d
    	          FROM ($sql) t1";
	    
	    $sqlPeriod = $this->allPeriod($startDate, $endDate);
	    
	    $sql = "SELECT t1.*, IFNULL(t2.amount, 0) AS amount, YEAR(t1.dt) as y, MONTH(t1.dt)-1 as m, DATE_FORMAT(t1.dt,'%e') as d
            	  FROM ($sqlPeriod) t1
    	          LEFT JOIN ($sql) t2
    	            ON t1.dt = t2.dt
    	         ORDER BY t1.dt ASC";
        return DB::select($sql);
    }
    
    public function allPeriod($startDate, $endDate) {
        $sql = "select datediff( '$endDate', '$startDate' ) as days";
        $days = DB::select($sql);
        $days = $days[0]->days;

        $dateSql = "";
        $days++;
        for ($i = 0; $i < $days; $i++) {
            $dateSql.="SELECT DATE_ADD('$startDate', INTERVAL $i day) AS dt";
            if( $i != $days - 1  ){
                $dateSql.=" UNION ALL ";
            }
        }
        return $dateSql;
    }
    
    public function totalRevenue($startDate, $endDate) {
        $prefix = DB::getTablePrefix();
	    $sql = "SELECT IFNULL(SUM(amount), 0) AS totalRevenue
	              FROM ".$prefix."transaction
	             WHERE (DATE(created_at) BETWEEN '$startDate' AND '$endDate')
    	           AND is_paid";
        $result = DB::select($sql);
        return $result[0]->totalRevenue;
    }
    
    public function avgUserPostProject($startDate, $endDate) {
        $prefix = DB::getTablePrefix();
	    $sql = "SELECT ROUND(IFNULL(AVG(cnt), 0), 1) as avgPost
                  FROM
                     (
                       SELECT user_id, count(*) cnt
                         FROM ".$prefix."job
	                    WHERE (DATE(created_at) BETWEEN '$startDate' AND '$endDate')
	                    GROUP BY user_id
                      ) t1";
        $result = DB::select($sql);
        return $result[0]->avgPost;
	}
    
	public function avgUserBidProject($startDate, $endDate) {
	    $prefix = DB::getTablePrefix();
	    $sql = "SELECT ROUND(IFNULL(AVG(cnt), 0), 1) as avgBid
                  FROM
                     (
                       SELECT user_id, count(*) cnt
                         FROM ".$prefix."bid
                        WHERE (DATE(created_at) BETWEEN '$startDate' AND '$endDate')
                        GROUP BY user_id
                     ) t1";
        $result = DB::select($sql);
	    return $result[0]->avgBid;
	}
    
	public function avgUserBidRate($startDate, $endDate) {
	    $prefix = DB::getTablePrefix();
        $sql = "
            SELECT ROUND(IFNULL(AVG(percent), 0), 1) AS avgPercent
              FROM (
            	SELECT t1.user_id, ( t1.bid_cnt / t1.biddable_cnt ) * 100 AS percent
            	  FROM
            		(
            		SELECT t1.user_id, t1.cnt AS biddable_cnt, IFNULL(t2.cnt, 0) AS bid_cnt
            		  FROM
            			(
            			SELECT user_id, COUNT(*) cnt
            			  FROM
            				(
            				SELECT t1.*
            				  FROM ".$prefix."user_sub_category t1, ".$prefix."job t2
            				 WHERE t1.sub_category_id = t2.sub_category_id
            				) t1
            			 GROUP BY t1.user_id
            			) t1
            		  LEFT JOIN
            			(
            			SELECT user_id, COUNT(*) cnt
            			  FROM ".$prefix."bid
                         WHERE (DATE(created_at) BETWEEN '$startDate' AND '$endDate')
                         GROUP BY user_id
            			) t2
            		    ON t1.user_id = t2.user_id
            		) t1
    	        ) t1";
        $result = DB::select($sql);
        return $result[0]->avgPercent;
	}    
}
