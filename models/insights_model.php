<?php


class Insights_Model extends Model
{
	
	function __construct()
	{
		parent::__construct();
	}

	public function oldSum()
	{
		
		$sql = "SELECT 
	          sum(a.sumtotal) as 'sumtotal'
	        , sum(a.sumqty) as 'sumqty'
	        , sum(a.sumperiod) as 'sumperiod'

		FROM (( 
		 SELECT
		sum(a.book_receipt) as 'sumtotal', 0 as 'sumqty', 0 as 'sumperiod'
		FROM `booking` a
		WHERE a.book_date BETWEEN CONCAT(DATE_FORMAT(NOW(),'%Y-%m-'),'01 00:00:00') AND CONCAT(LAST_DAY(NOW()),' 23:59:59')
		AND a.status <> 40
		AND book_receipt > 0
		) UNION ( 
		     SELECT
		0 as 'sumtotal',sum(a.book_list_qty) as 'sumqty',0 as 'sumperiod'
		FROM `booking_list` a
		LEFT OUTER JOIN booking b
		on a.book_code = b.book_code
		WHERE b.book_date BETWEEN CONCAT(DATE_FORMAT(NOW(),'%Y-%m-'),'01 00:00:00') AND CONCAT(LAST_DAY(NOW()),' 23:59:59')
		AND b.status <> 40
		AND a.book_list_code IN('1','2','3','4','5')
		) UNION ( 
		  SELECT
		  0 as 'sumtotal',0 as 'sumqty', COUNT(per_id) as 'sumperiod'
		    FROM period a
		    WHERE a.status = 1
		 )) as a ";

		$sth = $this->db->prepare($sql);
		$sth->execute( array() );

		return $sth->fetch( PDO::FETCH_ASSOC );

		
	}

	public function receipt($options=array())
	{
		$options = array_merge(array(
    		'start' => date('Y-m-01'),
    		'end' => date('Y-m-t'),
    	), $options);

		$sth = $this->db->prepare("SELECT SUM(book_receipt) as total FROM booking WHERE status!=40 AND book_date BETWEEN :startDate AND :endDate");
		$sth->execute( array(
			':startDate' => $options['start'],
			':endDate' => $options['end'],
		) );

		$result = $sth->fetch( PDO::FETCH_ASSOC );
		return floatval($result['total']);
	}

    public function incomeYearly( $options=array() )
    {
    	$options = array_merge(array(
    		'year' => date('Y'),
    	), $options);

    	$options['title'] = "กราฟแสดงยอดปี {$options['year']}";

    	$month = array(1=>"มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน", "กรกฎาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม");
        $results = array();

        for ($i=1; $i <= 12; $i++) { 

	        $m = $i < 10 ? "0{$i}": $i;

            $lastYear = $options['year']-1;
	        $results[] = array(
	            'name' => $month[$i],
	            'value' => $this->receipt(array(
	                'start' => date("{$options['year']}-{$m}-01"),
	                'end' => date("{$options['year']}-{$m}-t"),
	            )),
                'value2' => $this->receipt(array(
                    'start' => date("{$lastYear}-{$m}-01"),
                    'end' => date("{$lastYear}-{$m}-t"),
                )),
	        );
	    }

	    $chart = array();

    	// $t = strtotime($start);
    	$fdata = array();
    	$lists = array();
        $number = array();
    	$categories = array();

        $chart['tooltip'] = array(
            'enabled'=>true
        );
        
        // เลขหัวกราฟ
        $chart['plotOptions'] = array(
            'line' => array(
                // 'stacking' => 'normal', // กราฟ ซ้อน
	        	'dataLabels' => array( 
	                'enabled'=> 'true' 
	            ),
	        	// 'enableMouseTracking' => false
            )
        );
        
        $series = array();
        $series[] = array(
            'type'=> 'line',
            'borderWidth'=> 0,
            'borderColor'=> null,
            'data'=> array(),
            'name' => "ยอดปี ".($options['year']-1),
            'color' => '#eeeeee',
        );
        $series[] = array(
            'type'=> 'line',
            'borderWidth'=> 0,
            'borderColor'=> null,
            'data'=> array(),
            'name' => "ยอดปี {$options['year']}",
        );


        foreach ($results as $key => $value){
            $categories[] = $value['name'];

            $series[0]['data'][] = intval($value['value2']);
            $series[1]['data'][] = intval($value['value']);
        }
        
        if( empty($categories) ){
            $categories[] = 'ไม่พบข้อมูล';
        }
        

        if( !empty($options['title']) ){
        	$chart['title'] = array(
	        	'text' => $options['title']
	        );
        }

        //ฐานล่าง
        $chart['xAxis'] = array(
        	'lineColor' => "#99ffbb",
        	'lineWidth' => 3,
        	// 'tickWidth' => 0,
        	'tickColor' => '#99ffbb',
        	'categories' => $categories
        );

        foreach ($series as $key => $value) {
            $chart['series'][] = $value;
        }

       	$chart['chart'] = array(
       		'type'=> 'line',
       	);
        
        $chart['legend'] = array(

            'enabled'=> 1,

            'align'=> 'right',
            'x'=> -30,
            'verticalAlign'=> 'top',
            'y'=> 10,
            'floating'=> true,
            'backgroundColor'=> 'white',
            'borderColor'=> '#CCC',
            'borderWidth'=> 1,
            'shadow'=> false,
        );

        return $chart;

    }


    public function topAgencyChart($options=array())
    {

    	$options = array_merge(array(
    		'title' => 'Agency Top 10',
    		'start' => date('Y-01-01'),
    		'end' => date('Y-12-t'),
    	), $options);

    	$options['title'] .= " | ". date('j M', strtotime($options['start'])).' - '.date('j M Y', strtotime($options['end']));


    	$sql = "
    		SELECT 
    			  agency.agen_fname as fname
    		    , agency.agen_lname as lname
    		    , agency.agen_nickname as nickname

    		    , company.agen_com_name as company_name

    			, SUM(booking.book_receipt) AS 'value'

            FROM `booking`  
            	INNER JOIN 
            		(`agency` INNER JOIN `agency_company` as company ON company.agen_com_id=agency.agency_company_id
            	) ON booking.agen_id=agency.agen_id

            WHERE 
            	    booking.book_date BETWEEN :startDate AND :endDate
            	AND booking.status<>40

            GROUP by booking.agen_id
            ORDER BY SUM(booking.book_receipt) DESC

            LIMIT 10
        ";

        $results = $this->db->select($sql, array(':startDate'=>$options['start'], ':endDate'=>$options['end']));

    	$chart = array();

    	// $t = strtotime($start);
    	$fdata = array();
    	$lists = array();
        $number = array();
    	$categories = array();

        $chart['tooltip'] = array(
            'enabled'=>true
        );
        
        // เลขหัวกราฟ
        $chart['plotOptions'] = array(
            'column' => array(
	        	'dataLabels' => array( 
	                'enabled'=> 'true' 
	            ),
	        	// 'enableMouseTracking' => false
            )
        );

        
        foreach ($results as $key => $value){

        	$name = trim($value['nickname'], '-');
        	if( empty($name) ){
        		$name = trim("{$value['fname']} {$value['lname']}", '-');
        	}

        	if( empty($name) ){
        		$name = "{$value['company_name']}";
        	}
        	else{
        		$name .= "({$value['company_name']})";
        	}
        	
            $categories[] = trim($name);
            $lists[] = intval($value['value']);
            // $number[] = intval($value['model_amount_balance'] + $value['model_amount_sales']);
        }
        
        if( empty($categories) ){
            $categories[] = 'ไม่พบข้อมูล';
        }
        
        if( !empty($options['title']) ){
        	$chart['title'] = array(
	        	'text' => $options['title']
	        );
        }
        

        //ฐานล่าง
        $chart['xAxis'] = array(
        	'lineColor' => "#99ffbb",
        	'lineWidth' => 3,
        	'tickWidth' => 0,
        	'categories' => $categories
        );

        $chart['series'][] = array(
        	// 'type'=> 'line',
            'borderWidth'=> 0,
            'borderColor'=> null,
            'data'=> $lists,
            'name' => 'ยอด',
            'showInLegend'=> false,
        );
        
        $chart['legend'] = array(

            'enabled'=> 1,

            'align'=> 'right',
            'x'=> -30,
            'verticalAlign'=> 'top',
            'y'=> 10,
            'floating'=> true,
            'backgroundColor'=> 'white',
            'borderColor'=> '#CCC',
            'borderWidth'=> 1,
            'shadow'=> false,
        );

        return $chart;
    }

    public function topSalesChart($options=array())
    {

    	$options = array_merge(array(
    		'title' => 'Sales Top 10',
    		'start' => date('Y-01-01'),
    		'end' => date('Y-12-t'),
    	), $options);

    	$options['title'] .= " | ". date('j M', strtotime($options['start'])).' - '.date('j M Y', strtotime($options['end']));

    	$sql = "
    		SELECT 
    			  user.user_fname
    		    , user.user_lname
    		    , user.user_nickname
    		    
    			, SUM(booking.book_receipt) AS 'value'

            FROM `booking`  
            	INNER JOIN `user` ON booking.user_id=user.user_id

            WHERE 
            	    booking.book_date BETWEEN :startDate AND :endDate
            	AND booking.status<>40

            GROUP by booking.user_id
            ORDER BY SUM(booking.book_receipt) DESC

            LIMIT 10
        ";

        $results = $this->db->select($sql, array(':startDate'=>$options['start'], ':endDate'=>$options['end']));


    	$chart = array();

    	// $t = strtotime($start);
    	$fdata = array();
    	$lists = array();
        $number = array();
    	$categories = array();

        $chart['tooltip'] = array(
            'enabled'=>true
        );
        
        // เลขหัวกราฟ
        $chart['plotOptions'] = array(
            'column' => array(
                // 'stacking' => 'normal', // กราฟ ซ้อน
	        	'dataLabels' => array( 
	                'enabled'=> 'true' 
				),
	        	// 'enableMouseTracking' => false
	        )
        );


        foreach ($results as $key => $value){

        	$name = $value['user_nickname'];
        	if( empty($name) ){
        		$name = "{$value['user_fname']} {$value['user_lname']}";
        	}
            $categories[] = trim($name);
            $lists[] = intval($value['value']);
        }
        
        if( empty($categories) ){
            $categories[] = 'ไม่พบข้อมูล';
        }
        
        if( !empty($options['title']) ){
        	$chart['title'] = array(
	        	'text' => $options['title']
	        );
        }
        

        //ฐานล่าง
        $chart['xAxis'] = array(
        	'lineColor' => "#99ffbb",
        	'lineWidth' => 3,
        	'tickWidth' => 0,
        	'categories' => $categories
        );

        $chart['series'][] = array(
            'borderWidth'=> 0,
            'borderColor'=> null,
            'data'=> $lists,
            'name' => 'ยอด',
            'showInLegend'=> false,
            'color' => '#ffb935',
        );
        
        
        $chart['legend'] = array(

            'enabled'=> 1,

            'align'=> 'right',
            'x'=> -30,
            'verticalAlign'=> 'top',
            'y'=> 10,
            'floating'=> true,
            'backgroundColor'=> 'white',
            'borderColor'=> '#CCC',
            'borderWidth'=> 1,
            'shadow'=> false,
        );

        return $chart;
    }

    public function topSeriesChart($options=array())
    {

    	$options = array_merge(array(
    		'title' => 'Series Top 10',
    		'start' => date('Y-01-01'),
    		'end' => date('Y-12-t'),
    	), $options);

    	$options['title'] .= " | ". date('j M', strtotime($options['start'])).' - '.date('j M Y', strtotime($options['end']));

    	$sql = "
    		SELECT 
    			  series.ser_name as name
    		    , series.ser_code as code
    		    
    			, SUM(booking.book_receipt) AS 'value'

            FROM `booking`  
            	INNER JOIN (
            		`period` INNER JOIN series ON series.ser_id=period.ser_id
            	) ON booking.per_id=period.per_id

            WHERE 
            	    booking.book_date BETWEEN :startDate AND :endDate
            	AND booking.status<>40

            GROUP by booking.user_id
            ORDER BY SUM(booking.book_receipt) DESC

            LIMIT 10
        ";
        $results = $this->db->select($sql, array(':startDate'=>$options['start'], ':endDate'=>$options['end']));
        // print_r($results); die;


    	$chart = array();
    	$fdata = array();
    	$lists = array();
        $number = array();
    	$categories = array();


    	foreach ($results as $key => $value){

        	$name = "{$value['code']} - {$value['name']}";
            $categories[] = trim($name);
            $lists[] = intval($value['value']);
        }


        $chart['tooltip'] = array(
            'enabled'=>true
        );
        
        // เลขหัวกราฟ
        $chart['plotOptions'] = array(
            'column' => array(
        	'dataLabels' => array( 
                'enabled'=> 'true' 
            ),
        	// 'enableMouseTracking' => false
            )
        );

        if( empty($categories) ){
            $categories[] = 'ไม่พบข้อมูล';
        }
        
        if( !empty($options['title']) ){
        	$chart['title'] = array(
	        	'text' => $options['title']
	        );
        }
        

        //ฐานล่าง
        $chart['xAxis'] = array(
        	'lineColor' => "#99ffbb",
        	'lineWidth' => 3,
        	'tickWidth' => 0,
        	'categories' => $categories
        );

        $chart['series'][] = array(
            'borderWidth'=> 0,
            'borderColor'=> null,
            'data'=> $lists,
            'name' => 'ยอด',
            'showInLegend'=> false,
            'color' => '#5fba7d',
        );
        
        /*$chart['series'][] = array(
            'borderWidth'=> 0,
            'borderColor'=> null,
            'data'=> $number,
            'name' => 'จำนวนรถยนต์ทั้งหมด'
        );*/
        
        
        $chart['legend'] = array(

            'enabled'=> 1,

            'align'=> 'right',
            'x'=> -30,
            'verticalAlign'=> 'top',
            'y'=> 10,
            'floating'=> true,
            'backgroundColor'=> 'white',
            'borderColor'=> '#CCC',
            'borderWidth'=> 1,
            'shadow'=> false,
        );

        return $chart;
    }
}