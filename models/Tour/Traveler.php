<?php

class Traveler_Tour extends Model
{
	public function __construct() {
		parent::__construct();
    }

    private $__obj = 'room_detail';
    private $__table = "

        room_detail as traveler
            LEFT JOIN ( booking 
                LEFT JOIN (
                    period 
                        INNER JOIN bus_list as bus ON bus.per_id=period.per_id
                        LEFT JOIN series ON series.ser_id=period.ser_id
                ) ON booking.per_id=period.per_id
            ) ON booking.book_code=traveler.book_code

        ";

    /*
    SELECT traveler.* FROM room_detail as traveler 
        LEFT JOIN (
            booking LEFT JOIN 
                (period INNER JOIN bus_list as bus ON bus.per_id=period.per_id
            ) ON period.per_id=booking.per_id
        ) ON booking.book_code=traveler.book_code 
        
        WHERE period.per_id=856 AND bus.bus_no=1
    ORDER BY traveler.room_id ASC 
    */

    private $__select = "
          
          traveler.room_id as id
        , booking.book_id
        , booking.book_code

        , traveler.room_prename
        , traveler.room_fname
        , traveler.room_lname
        , traveler.room_name_thai as name_th
        , traveler.room_sex as sex
        , traveler.room_no

        , traveler.room_passportno as passportno
        , traveler.room_birthday as birthday
        
        , traveler.room_country as country
        , traveler.room_nationality as nationality
        
        , traveler.room_type
        , booking.book_room_twin as room_twin
        , booking.book_room_double as room_double
        , booking.book_room_triple as room_triple
        , booking.book_room_tripletwin as room_tripletwin
        , booking.book_room_single as room_single

        , period.per_date_start as date_start
        , period.per_date_end as date_end

        , series.ser_name as title
        , series.ser_code as code
        
    ";
    private $__prefixField = '';


    public function lists($options=array())
    {
        $results = $this->find( $options );

        return $results['items'];
    }
    
    public function find($options=array())
    {
    	foreach (array('period', 'bus') as $key) {
            if( isset($_REQUEST[$key]) ){
                $options[$key] = $_REQUEST[$key];
            }
        }

    	$options = array_merge(array(
            // 'more' => true,
            'sort' => isset($_REQUEST['sort'])? $_REQUEST['sort']: 'booking.book_code, traveler.room_no',
            'time'=> isset($_REQUEST['time'])? $_REQUEST['time']:time(),
        ), $options);

    	/* --------------------------------- */
    	/* ----------- condition ----------- */
    	/* --------------------------------- */
    	$condition = '';
    	$params = array();

    	if( isset($options['period']) ){
            $condition .= !empty($condition) ? ' AND ':'';
            $condition .= "period.per_id=:period";
            $params[':period'] = $options['period'];
        }
        if( isset($options['bus']) ){
            $condition .= !empty($condition) ? ' AND ':'';
            $condition .= "bus.bus_no=:bus";
            $params[':bus'] = $options['bus'];
        }

        $condition .= !empty($condition) ? ' AND ':'';
        $condition .= "booking.status NOT IN(5,40,50)";

        /* ----------------------------------- */
    	/* --------- END: condition ---------- */
    	/* ----------------------------------- */

        $where = !empty($condition) ? "WHERE {$condition}":'';
        $groupById = '';
        $having = !empty($having) ? "HAVING {$having}":'';

        $orderby = !empty($options['sort']) ? "ORDER BY {$options['sort']}":'';
        $limit = '';

        // book_code

        $sql = "SELECT SQL_CALC_FOUND_ROWS {$this->__select} FROM {$this->__table} {$where} {$groupById} {$having} {$orderby} {$limit}";
        // echo $sql; die;

        $results = $this->db->select($sql, $params);
        $sth = $this->db->prepare("SELECT FOUND_ROWS() as total");
        $sth->execute();
        $found_rows = $sth->fetch( PDO::FETCH_ASSOC );
        $arr['total'] = $found_rows['total'];
        $arr['items'] = $this->buildFrag( $results, $options );


        $arr['options'] = $options;
        return $arr;
    }


    /**/
    /* -- convert data -- */
    /**/
    public function buildFrag($results, $options=array()) {
        $data = array();
        foreach ($results as $key => $value) { if( empty($value) ) continue; $data[] = $this->convert( $value, $options ); }
        return $data;
    }
    public function convert($data, $options=array()){
    	// $data = $this->__cutPrefixField($this->__prefixField, $data);

        $data['date_str'] = date('j', strtotime($data['date_start'])).'-'.date('j M Y', strtotime($data['date_end']));

        // $data['meta_str'] = "{$data['ser_go_flight_code']}({$data['ser_go_time']}) - {$data['ser_return_flight_code']}({$data['ser_return_time']})";

        $data['name'] = trim("{$data['room_prename']} {$data['room_fname']} {$data['room_lname']}");
        // $data['name_th'] = trim("{$data['room_name_thai']}");

        unset($data['room_prename']);
        unset($data['room_fname']);
        unset($data['room_lname']);
        // unset($data['room_name_thai']);

         // $this->fn->q('time')->str_event_date( $data['date_start'], $data['date_end'] );
    	return $data;
    }

    public function leader($period, $bus)
    {
        $sth = $this->db->prepare("SELECT 

              per_leader_id as id
            , room_prename
            , room_fname
            , room_lname
            , room_sex as sex
            , room_name_thai as name_th

         FROM period_leader WHERE per_id=:period AND bus_no=:bus LIMIT 1");
        $sth->execute( array( ':period'=>$period, ':bus'=>$bus  ) );

        $data = $sth->fetch( PDO::FETCH_ASSOC );
        if( !empty($data) ){
            $data['name'] = trim("{$data['room_prename']}{$data['room_fname']} {$data['room_lname']}");

            unset($data['room_prename']);
            unset($data['room_fname']);
            unset($data['room_lname']);
        }

        return $data;
    }


    public function plan($period, $bus)
    {
        $sth = $this->db->prepare("

        SELECT 

              period.per_date_start as date_start
            , period.per_date_end as date_end

            , series.ser_name as title
            , series.ser_code as code

            , series.ser_go_flight_code
            , series.ser_go_route
            , series.ser_go_time

            , series.ser_return_flight_code
            , series.ser_return_route
            , series.ser_return_time

            , country.country_id
            , country.country_name
            , country.country_code
            , country.tagbag_code

            , city.city_id
            , city.city_name

            , airline.air_id
            , airline.air_name
            , airline.air_code

        FROM 

            period 
                INNER JOIN bus_list as bus ON bus.per_id=period.per_id AND bus.bus_no=:bus 
                LEFT JOIN (
                    series 
                        LEFT JOIN country ON series.country_id=country.country_id
                        LEFT JOIN series_location_city as city ON series.city_id=city.city_id
                        LEFT JOIN airline ON series.air_id=airline.air_id
                    
                ) ON series.ser_id=period.ser_id
        WHERE period.per_id=:period

        LIMIT 1");

        $sth->execute( array( ':period'=>$period, ':bus'=>$bus  ) );


        $data = array();
        if( $sth->rowCount() ){
            $data = $sth->fetch( PDO::FETCH_ASSOC );


            $data['date_str'] = date('j', strtotime($data['date_start'])).'-'.date('j M Y', strtotime($data['date_end']));

            $data['flight_str'] = "{$data['ser_go_flight_code']}({$data['ser_go_time']}) - {$data['ser_return_flight_code']}({$data['ser_return_time']})";


            /*unset($data['ser_go_flight_code']);
            unset($data['ser_go_route']);
            unset($data['ser_go_time']);

            unset($data['ser_return_flight_code']);
            unset($data['ser_return_route']);
            unset($data['ser_return_time']);*/
        }


        return $data;
    }
}