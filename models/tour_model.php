<?php

require_once 'Tour/Country_Tour.php';
require_once 'Tour/City_Tour.php';

require_once 'Tour/Airline_Tour.php';

require_once 'Tour/Period_Tour.php';


require_once 'Tour/Booking.php';
require_once 'Tour/Traveler.php';


require_once 'Tour/Category_Tour.php';


class Tour_Model extends Model
{
	public function __construct() {
		parent::__construct();

        $this->country = new Country_Tour();
        $this->city = new City_Tour();
        $this->airline = new Airline_Tour();


        $this->period = new Period_Tour();
        

        $this->booking = new Booking_Tour();
        $this->traveler = new Traveler_Tour();


        $this->category = new Category_Tour();
    }


    private $_table = "
        series

            LEFT JOIN period ON series.ser_id=period.ser_id

            LEFT JOIN country ON series.country_id=country.country_id
            LEFT JOIN tour_location_city as city ON series.city_id=city.city_id
            LEFT JOIN airline ON series.air_id=airline.air_id
    
            LEFT JOIN user uCreate ON uCreate.user_id=series.create_user_id
            LEFT JOIN user uUpdate ON uUpdate.user_id=series.update_user_id
    ";

    // ) LEFT JOIN period ON period.ser_id=series.ser_id

    private $_field = "
          series.ser_id
        , series.ser_name
        , series.ser_code

        , series.status
        
        , series.ser_price
        , series.ser_deposit

        , series.ser_go_flight_code
        , series.ser_go_route
        , series.ser_go_time

        , series.ser_return_flight_code
        , series.ser_return_route
        , series.ser_return_time
        
        , series.create_date
        , uCreate.user_fname as create_by_fname
        , uCreate.user_lname as create_by_lname

        , series.update_date
        , uUpdate.user_fname as update_by_fname
        , uUpdate.user_lname as update_by_lname


        , series.ser_url_word
        , series.ser_url_pdf
        , series.ser_url_img_1

        , country.country_id
        , country.country_name
        , country.country_code

        , airline.air_id 
        , airline.air_name 
        , airline.air_code 

        , city.city_id
        , city.city_name

        , SUM( CASE 
            WHEN period.status=1 AND DATE(now()) < period.per_date_start THEN 1
            ELSE 0
        END ) as period_total
    ";
    private $_groupby = 'series.ser_id';
    private $_prefixField = 'ser_';
    private $_uploadFolder = 'travel/';

    public function get($id, $option=array())
    {
        return $this->findById($id, $option);
    }
    public function findById($id, $option=array())
	{
        $_field = $this->_field;

        if( !empty($option['_field']) ){
            $_field .= ", {$option['_field']}";
        }
        $sql = "SELECT {$_field} FROM {$this->_table} WHERE series.ser_id=:id GROUP BY {$this->_groupby} LIMIT 1";
        // echo $sql; die;

		$sth = $this->db->prepare($sql);
        $sth->execute( array( ':id' => $id  ) );
        return $sth->rowCount()==1 ? $this->convert( $sth->fetch( PDO::FETCH_ASSOC ) ): array();
	}
    public function lists($option=array())
    {
        $this->_field = "series.ser_id, series.ser_name, series.ser_code";
        $results = $this->find( array_merge(array(
            'unlimit' => 1
        ),$option) );

        return $results['items'];
    }
    public function find($options=array())
    {
        foreach (array('q', 'country', 'city', 'airline', 'status', 'has_period', 'startDate', 'endDate', 'ser') as $key) {
            if( isset($_REQUEST[$key]) ){
                $options[$key] = $_REQUEST[$key];
            }
        }

    	$options = array_merge(array(
            'more' => true,

            'limit' => isset($_REQUEST['limit'])? $_REQUEST['limit']: 50,
            'page' => isset($_REQUEST['page'])? $_REQUEST['page']: 1,

            'sort' => isset($_REQUEST['sort'])? $_REQUEST['sort']: 'series.create_date DESC',
            // 'dir' => isset($_REQUEST['dir'])? $_REQUEST['dir']: 'DESC',

            'time'=> isset($_REQUEST['time'])? $_REQUEST['time']:time(),

        ), $options);

        if( !empty($options['unlimit']) ){
            unset($options['limit']); unset($options['page']);
        }

        $condition = "";
        $params = array();

        $condition2 = '';

        
        if( !empty($options['q']) ){
            $condition .= !empty($condition) ? ' AND ':'';
            $qLower = strtolower($options['q']);
            $qUpper = strtoupper($options['q']);
            $condition .= "(
                series.ser_name LIKE '%{$options['q']}%' OR 
                series.ser_code LIKE '%{$options['q']}%' OR 
                series.ser_code='{$qUpper}' OR 
                series.ser_code='{$qLower}'

            )";
        }

        if( !empty($options['country']) ){
            $condition .= !empty($condition) ? ' AND ':'';
            $condition .= "series.country_id={$options['country']}";
        }

        if( !empty($options['airline']) ){
            $condition .= !empty($condition) ? ' AND ':'';
            $condition .= "series.air_id={$options['airline']}";
        }

        if( !empty($options['ser']) ){
            $condition .= !empty($condition) ? ' AND ':'';
            $condition .= "series.ser_id=:ser";
            $params[':ser'] = $options['ser'];
        }

        if( !empty($options['city']) ){
            $condition .= !empty($condition) ? ' AND ':'';
            $condition .= "series.city_id=:city";
            $params[':city'] = $options['city'];
        }

        if( isset($options['status']) ){

            if( is_numeric($options['status']) ){

                if($options['status']==3){

                    $condition2 .= !empty($condition2) ? ' AND ':'';
                    $condition2 .= "period_total=0";

                    $condition .= !empty($condition) ? ' AND ':'';
                    $condition .= "(series.status NOT IN(0,2) )";
                    // $params[':stat'] = 2;
                }
                else{
                    $condition .= !empty($condition) ? ' AND ':'';
                    $condition .= "series.status=:status";
                    $params[':status'] = $options['status'];

                    if( $options['status']==1 ){
                        $condition2 .= !empty($condition2) ? ' AND ':'';
                        $condition2 .= "period_total>0";
                    }
                }
            }  
        }
        
        if( !empty($options['has_period']) ){
            $condition2 .= !empty($condition2) ? ' AND ':'';
            $condition2 .= "period_total>0";
        }

        if( !empty($options['startDate']) && !empty($options['endDate']) ){

            $condition .= !empty($condition) ? ' AND ':'';
            $condition .= "(period.per_date_start BETWEEN :startDate AND :endDate)";

            $params[':startDate'] = date('Y-m-d', strtotime($options['startDate']));
            $params[':endDate'] = date('Y-m-d', strtotime($options['endDate']));
        }
        
        // $arr['total'] = $this->db->count($this->_table, $condition, $params);


        $limit = !empty($options['limit']) && !empty($options['page']) ? $this->limited( $options['limit'], $options['page'] ):'';
        $orderby = "ORDER BY {$options['sort']}"; // $this->orderby( $options['sort'], $options['dir'] );

        // groupby product.product_code
        // $today = date('Y-m-d');
        $where = !empty($condition) ? "WHERE {$condition}":'';
        $having = !empty($condition2) ? "HAVING {$condition2}":'';
        $sql = "
            SELECT 

            SQL_CALC_FOUND_ROWS

            {$this->_field}

            FROM 
                series

                LEFT JOIN period ON series.ser_id=period.ser_id
    
                LEFT JOIN country ON series.country_id=country.country_id
                LEFT JOIN tour_location_city as city ON series.city_id=city.city_id
                LEFT JOIN airline ON series.air_id=airline.air_id
        
                LEFT JOIN user uCreate ON uCreate.user_id=series.create_user_id
                LEFT JOIN user uUpdate ON uUpdate.user_id=series.update_user_id

            {$where} GROUP BY series.ser_id {$having} {$orderby} {$limit}

        ";
        // echo $sql; die;
        $results = $this->db->select($sql, $params);

        $sth = $this->db->prepare("SELECT FOUND_ROWS() as total");
        $sth->execute();
        $found_rows = $sth->fetch( PDO::FETCH_ASSOC );
        $arr['total'] = $found_rows['total'];
        $arr['items'] = $this->buildFrag( $results, $options );

        // print_r($arr); die;

        // 
        if( !empty($options['limit']) ){
        	if( ($options['page']*$options['limit']) >= $arr['total'] ) $options['more'] = false;
        }
        else{
        	$options['more'] = false;
        }

        $arr['options'] = $options;
        return $arr;
    }
    
    /* -- convert data -- */
    public function buildFrag($results, $options=array()) {
        $data = array();
        foreach ($results as $key => $value) { if( empty($value) ) continue; $data[] = $this->convert( $value, $options ); }
        return $data;
    }
    public function convert($data, $options=array()){

        $data = $this->__cutPrefixField($this->_prefixField, $data);
        // $data['permit']['del'] = 1;


        if( !empty($data['url_img_1']) ){

            $filename = $data['url_img_1'];
            $ext = explode('/', $filename);
            if( count($ext)>1 ){
                $filename = strtolower(substr(strrchr($filename,"/"),1));
            }

            $data['image_url'] = UPLOADS.$this->_uploadFolder.$filename;
            $data['banner_url'] = $data['image_url'];
        }


        if( !empty($data['url_word']) ){

            $filename = $data['url_word'];
            $ext = explode('/', $filename);
            if( count($ext)>1 ){
                $filename = strtolower(substr(strrchr($filename,"/"),1));
            }

            $data['word_url'] = UPLOADS.$this->_uploadFolder.$filename;
        }

        if( !empty($data['url_pdf']) ){

            $filename = $data['url_pdf'];
            $ext = explode('/', $filename);
            if( count($ext)>1 ){
                $filename = strtolower(substr(strrchr($filename,"/"),1));
            }

            $data['pdf_url'] = UPLOADS.$this->_uploadFolder.$filename;
        }


        if( !empty($data['air_code']) ){
            $data['air_name'].= '('. strtoupper($data['air_code']).')';
        }

        if( !empty($data['create_date']) ){
            $data['create_date_str'] = $this->fn->q('time')->stamp($data['create_date']); //date('j M Y', strtotime($data['create_date']));
            $data['create_by_name'] = trim("{$data['create_by_fname']} {$data['create_by_lname']}");
        }
        

        if( !empty($data['update_date']) ){
            $data['update_date_str'] = $data['update_date']!='0000-00-00 00:00:00' ? $this->fn->q('time')->stamp($data['update_date']): '-'; 
            $data['update_by_name'] = trim("{$data['update_by_fname']} {$data['update_by_lname']}");
        }

        // period_total
        $data['period_total'] = !empty($data['period_total']) ? $data['period_total']: 0;




        # status
        // if( !empty($data['status']) ){
        $data['status_id'] = $data['status'];
        if( !in_array($data['status_id'], array(0,2)) && $data['period_total']==0 ){
            $data['status_id'] = 3;
        }
        $status = $this->getStatus($data['status_id']);
        $data['status_name'] = $status['name'];
        $data['status_background'] = $status['background'];



        if( !empty($options['with']) ){
            if( is_array($options['with']) ){
                foreach ($options['with'] as $key => $value) {
                    
                }
            }else{

                /*if( $options['with']=='period'  ){
                    $data[$options['with']] = $this->period->lists( array('series'=>$data['id'], 'state') )
                }*/

                /*$model = "{}"
                $this->*/
            }
        }
        // }


        $view_stype = !empty($options['view_stype']) ? $options['view_stype']: 'convert';
        if( !in_array($view_stype, array('bucketed')) ) $view_stype = 'convert';
        return $view_stype=='convert'? $data: $this->{$view_stype}( $data );
    }
    public function bucketed($data, $options=array()) {

        // print_r($data); die;
        $category = '';
        /*if( !empty($data['mobile']) ){
            $category = $data['mobile'];
        }*/

        return array(
            'id'=> $data['id'],
            'text'=> $data['name'],
            'subtext' => '',
            "category"=> $category,
            "code"=> $data['code'],
            "status"=> $data['status'],
            "image"=> $data['image_url'],

            'flag'=> strtolower($data['country_code']),

            'air_code'=> strtolower($data['air_code']),
            'air_name'=> $data['air_name'],

            'country_name'=> $data['country_name'],

            'create_date_str'=> $data['create_date_str'],
            'create_by_name'=> $data['create_by_name'],
            'update_date_str'=> $data['update_date_str'],
            'update_by_name'=> $data['update_by_name'],
            
        );
    }

	public function insert(&$data)
	{
		// if( !isset($data[$this->_prefixField.'enabled']) ) $data[$this->_prefixField.'enabled'] = 1;

		$this->db->insert('series', $data);
        $data['id'] = $this->db->lastInsertId();
	}

	public function update($id, $data)
	{
		$this->db->update('series', $data, "`ser_id`={$id}");
	}

    public function delete($id)
    {
        $this->db->delete( 'series', "{$this->_prefixField}id={$id}" );
    }


    public function status()
    {
        $status[0] = array('id'=>0, 'name' => 'ฉบับร่าง', 'hidden'=>true, 'background'=>'#aaa');
        $status[1] = array('id'=>1, 'name' => 'ใช้งาน', 'hidden'=>true, 'background'=>'#4CAF50');
        $status[2] = array('id'=>2, 'name' => 'ระงับ', 'hidden'=>true, 'background'=>'#333');
        $status[3] = array('id'=>3, 'name' => 'ไม่มีพีเรียด', 'hidden'=>false, 'background'=>'#605988');

        $status[9] = array('id'=>9, 'name' => 'ถังขยะ', 'hidden'=>false, 'background'=>'#f2f2f2');

        /*foreach ($status as $key => $value) {
            $status[$key]['count'] = $this->db->count($this->_objType, "`status`=:status", array(':status'=>$value['id']));
        }*/

        return $status;
    }
    public function getStatus($id)
    {
        $arr = $this->status(); 
        return !empty($arr[$id]) ? $arr[$id]: null;
    }

    public function suggestList()
    {
        $item[] = array('id'=>'ser_is_promote', 'value'=>'ser_is_promote', 'name' => 'โปรดันขาย');
        $item[] = array('id'=>'ser_is_recommend', 'name' => 'Recommend');

        return $item;
    }

    public function airlineList()
    {
        return $this->db->select("SELECT air_id as id, air_name as name FROM airline WHERE status=1 ORDER BY air_name");
    }

    public function countryList()
    {
        $country = $this->db->select("SELECT country_id as id, country_name as name FROM country WHERE status=1 ORDER BY country_name");

        foreach ($country as $key => $value) {
            
            $country[$key]['items'] = $this->db->select("SELECT city_id as id, city_name as name FROM tour_location_city WHERE city_country_id={$value['id']} AND city_enabled=1 ORDER BY city_name");
        }

        return $country;
    }


    public function codeList( $options=array() )
    {   

        foreach (array('country', 'city') as $key) {
            if( isset($_REQUEST[$key]) ){
                $options[$key] = $_REQUEST[$key];
            }
        }

        
        $condition = "series.status=:status";
        $params = array(':status'=>1);

        if( !empty($options['country']) ){
            $condition .= !empty($condition)? ' AND ': '';
            $condition .= 'series.country_id=:country';
            $params[':country'] = $options['country'];
        }

        if( !empty($options['city']) ){
            $condition .= !empty($condition)? ' AND ': '';
            $condition .= 'series.city_id=:city';
            $params[':city'] = $options['city'];
        }

        $sql = "
            SELECT 
                  series.ser_id as id
                , series.ser_name as name
                , series.ser_code as code 

                , country.country_id 
                , country.country_name

                , city.city_id 
                , city.city_name

            FROM series 
                LEFT JOIN country ON country.country_id=series.country_id
                LEFT JOIN tour_location_city as city ON city.city_id=series.city_id

            WHERE {$condition}
            ORDER BY series.ser_code
        ";
        // echo $sql; die;

        $results = $this->db->select($sql, $params);

        $country = array();
        foreach ($results as $key => $value) {
            
            if( empty($country[$value['country_id']]) ){

                $country[$value['country_id']] = array(
                    'id' => $value['country_id'],
                    'name' => $value['country_name'],
                    'items' => array(),
                );
            }

            $country[$value['country_id']]['items'][] = $value;
        }

        return $country;
    }


    // 
    public function salesForce( $options=array() )
    {
        $options['startDate'] = date("Y-m-d", strtotime("-1 day")); 
        $options['endDate'] = date("Y-m-d", strtotime("+1 week")); 

        foreach (array('q', 'country', 'airline', 'status', 'has_period', 'startDate', 'endDate') as $key) {
            if( isset($_REQUEST[$key]) ){
                $options[$key] = $_REQUEST[$key];
            }
        }

        $options = array_merge(array(
            'more' => false,

            'limit' => isset($_REQUEST['limit'])? $_REQUEST['limit']: 10,
            'page' => isset($_REQUEST['page'])? $_REQUEST['page']: 1,

            'sort' => isset($_REQUEST['sort'])? $_REQUEST['sort']: 'period.per_date_start ASC',

            'time'=> isset($_REQUEST['time'])? $_REQUEST['time']:time(),

        ), $options);
        
        $condition = '';
        $params = array();


        if( !empty($options['q']) ){
            $condition .= !empty($condition) ? ' AND ':'';
            $qLower = strtolower($options['q']);
            $qUpper = strtoupper($options['q']);
            $condition .= "(series.ser_name LIKE '%{$options['q']}%' OR series.ser_code='{$qUpper}' OR series.ser_code='{$qLower}')";
        }

        if( !empty($options['country']) ){
            $condition .= !empty($condition) ? ' AND ':'';
            $condition .= "series.country_id={$options['country']}";
        }

        if( !empty($options['airline']) ){
            $condition .= !empty($condition) ? ' AND ':'';
            $condition .= "series.air_id={$options['airline']}";
        }

        if( !empty($options['startDate']) && !empty($options['endDate']) ){
            $condition .= !empty($condition) ? ' AND ':'';
            $condition .= "(period.per_date_start BETWEEN :startDate AND :endDate)";

            $params[':startDate'] = date('Y-m-d', strtotime($options['startDate']));
            $params[':endDate'] = date('Y-m-d', strtotime($options['endDate']));
        }


        # Status
        $condition .= !empty($condition) ? ' AND ':'';
        $condition .= "series.status=:status AND period.status=:status";
        $params[':status'] = 1;
        
        // $arr['total'] = $this->db->count($this->_table, $condition, $params);


        $limit = !empty($options['limit']) && !empty($options['page']) ? $this->limited( $options['limit'], $options['page'] ):'';
        $orderby = "ORDER BY {$options['sort']}"; // $this->orderby( $options['sort'], $options['dir'] );

        // groupby product.product_code
        // $today = date('Y-m-d');
        $where = !empty($condition) ? "WHERE {$condition}":'';
        $having = !empty($having) ? "HAVING {$having}":'';


        $sql = "SELECT 

              SQL_CALC_FOUND_ROWS
              
                series.ser_code as code
              , bus.bus_no
              , bus.bus_qty as seat

              , period.per_id as id
              , period.per_date_start as date_start
              , period.per_date_end  as date_end
              , period.per_price_1 as price

            FROM bus_list as bus INNER JOIN period ON bus.per_id=period.per_id
                LEFT JOIN series ON series.ser_id=period.ser_id


            {$where} {$orderby} {$limit}

        ";
        // echo $sql; die;
        $results = $this->db->select($sql, $params);

        $sth = $this->db->prepare("SELECT FOUND_ROWS() as total");
        $sth->execute();
        $found_rows = $sth->fetch( PDO::FETCH_ASSOC );
        $arr['total'] = $found_rows['total'];


        foreach ($results as $key => $value) {

            // if( empty($items[$value['code']]) ) $items[$value['code']] = array();

            $time = strtotime($value['date_end']);

            $bookingList = $this->db->select("SELECT * FROM booking FROM booking.per_id=:period", array(':period'=>$value['id']));
            $results[$key]['bookingList'] = $bookingList;

            $countVal = array(
                'fullpayment' => 0,
                'wanted' => $value['seat'],
                'booking' => 0,
                'wishlist' => 0,
                'cancel' => 0,
            );

            foreach ($bookingList as $i => $data) {
                
                if( in_array($data['status'], array(0, 10, 20, 25, 30, 35)) ){
                    $countVal['booking']++;

                    if( $data['status']==35 ){
                        $countVal['fullpayment'] ++;
                    }
                }
                else if( $data['status'] == 50 ){
                    $countVal['wishlist'] ++;
                }
                else if( $data['status'] == 40 ){
                    $countVal['cancel'] ++;
                }
            }


            $results[$key]['count'] = $countVal;

            $results[$key]['bookingList'] = $this->db->select("SELECT * FROM booking FROM booking.per_id=:period", array(':period'=>$value['id']));

            $results[$key]['date_str'] = 
                  date('j', strtotime($value['date_start']))
                . '-'
                . date('j ',  $time)
                . $this->fn->q('time')->month( date('n',  $time) )
                . date(' Y', $time );

        }

        $arr['items'] = $results;

        
        if( ($options['page']*$options['limit']) < $arr['total'] ) $options['more'] = true;
        
        $arr['options'] = $options;
        return $arr;
    }


    public function upload( $id, $options )
    {
        $source = $options['userfile']['tmp_name'];
        $filename = $options['userfile']['name'];

        $filename = $this->fn->q('file')->createName($filename, $id, $options['type'], $options['user_id'] );

        if( copy($source, WWW_UPLOADS.$this->_uploadFolder.$filename) ){
            // 
            $dataPost[$options['field']] = $filename;
            $this->update( $id, $dataPost);
        }
    }
    public function removeFile($id, $filename, $field='')
    {
        $ext = explode('/', $filename);
        if( count($ext)>1 ){
            $filename = strtolower(substr(strrchr($filename,"/"),1));
        }

        $source =  WWW_UPLOADS.$this->_uploadFolder.$filename;
        if( file_exists($source) ){
            unlink($source);

            if( !empty($field) ){
                $data[$field] = '';
                $this->update($id, $data);
            }
        }
    }



    /**/
    /* category */
    /**/
    public function categoryList($id)
    {
        $results = $this->db->select("SELECT category.cry_id as id FROM tour_category as category INNER JOIN tour_series_category_permit as permit ON category.cry_id=permit.category_id WHERE category.cry_enabled=1 AND permit.serie_id=:id", array(':id'=>$id));

        $data = array();
        foreach ($results as $key => $value) {
            $data[] = $value['id'];
        }

        return $data;
    }
    public function categoryUpdate($id, $data)
    {
        $this->db->delete("tour_series_category_permit", "`serie_id`={$id}", count($this->categoryList($id)));

        foreach ($data as $category) {
            $this->db->insert("tour_series_category_permit", array('category_id'=>$category, 'serie_id'=>$id));
        }
    }
}
