<?php

require_once 'Booking/Booking_Detail.php';
require_once 'Booking/Booking_ExtraList.php';
require_once 'Booking/Booking_Discount.php';


class Booking_Model extends Model
{
	public function __construct() {
		parent::__construct();

        $this->detail = new Booking_Detail;
        $this->extralist = new Booking_ExtraList;
        $this->discount = new Booking_Discount;
    }


    private $_obj = 'booking';
    private $_table = "
        booking 
            LEFT JOIN (
                agency LEFT JOIN (
                    agency_company as company LEFT JOIN location_province ON company.location_province=location_province.province_id
                ) ON agency.agency_company_id=company.agen_com_id
            ) ON booking.agen_id=agency.agen_id

            LEFT JOIN (
                bus_list as bus
                    LEFT JOIN 
                        ( period LEFT JOIN series ON series.ser_id=period.ser_id 

                    ) ON bus.per_id=period.per_id
                    
            ) ON booking.per_id=bus.per_id AND booking.bus_no=bus.bus_no


            LEFT JOIN user ON user.user_id=booking.user_id

            LEFT JOIN user as createby ON createby.user_id=booking.create_user_id
            LEFT JOIN user as updateby ON updateby.user_id=booking.update_user_id
    ";
    private $_field = '
          booking.*

        , agency.agen_fname
        , agency.agen_lname
        , agency.agen_position
        , agency.agen_tel
        , agency.agen_line_id
        , agency.agen_email

        , company.agen_com_id as company_id
        , company.agen_com_name as company_name
        , company.agen_com_name_th as company_name_th
        , location_province.province_name as company_location_province_name

        , user.user_fname
        , user.user_lname
        , user.user_nickname

        , createby.user_fname as createby_fname
        , createby.user_lname as createby_lname
        , createby.user_nickname as createby_nickname

        , updateby.user_fname as updateby_fname
        , updateby.user_lname as updateby_lname
        , updateby.user_nickname as updateby_nickname

        , series.ser_code as tour_code

        , period.per_date_start as start_date
        , period.per_date_end as end_date
    ';
    private $_prefixField = 'book_';

    public function lists($options=array())
    {
        $options = array_merge(array(
            'unlimit' => true,
        ), $options);

        $results = $this->find( $options );
        return $results['items'];
    }
    public function get($id)
    {
        return $this->findById($id);
    }


    public function findById($id)
	{
		$sth = $this->db->prepare("SELECT {$this->_field} FROM {$this->_table} WHERE booking.book_id=:id LIMIT 1");
        $sth->execute( array( ':id' => $id  ) );
        return $sth->rowCount()==1 ? $this->convert( $sth->fetch( PDO::FETCH_ASSOC ) ): array();
	}
    public function find($options=array())
    {
        foreach (array('q', 'status', 'guarantee', 'country', 'city' , 'series', 'sales', 'startDate', 'endDate') as $key) {
            if( isset($_REQUEST[$key]) ){
                $options[$key] = $_REQUEST[$key];
            }
        }

    	$options = array_merge(array(
            'more' => true,

            'limit' => isset($_REQUEST['limit'])? $_REQUEST['limit']: 50,
            'page' => isset($_REQUEST['page'])? $_REQUEST['page']: 1,

            'sort' => isset($_REQUEST['sort'])? $_REQUEST['sort']: 'create',
            'dir' => isset($_REQUEST['dir'])? $_REQUEST['dir']: 'DESC',

            'time'=> isset($_REQUEST['time'])? $_REQUEST['time']:time(),

        ), $options);

        if( !empty($options['unlimit']) ){
            unset($options['limit']); unset($options['page']);
        }


        /* condition */
        $condition = "";
        $params = array();

        if( !empty($options['period']) ){
            $condition .= !empty($condition) ? ' AND ':'';
            $condition .= "per_id=:period";
            $params[':period'] = $options['period'];
        }

        if( !empty($options['bus']) ){
            $condition .= !empty($condition) ? ' AND ':'';
            $condition .= "bus_no=:bus";
            $params[':bus'] = $options['bus'];
        }

        if( !empty($options['q']) ){
            $condition .= !empty($condition) ? ' AND ':'';
            $condition .= "booking.book_code=:q OR series.ser_code=:q OR booking.book_code LIKE :qLast OR booking.book_code LIKE :qFirst";
            // $params[':qLower'] = '%'.(strtolower($options['q'])).'%';
            $params[':qFirst'] = "{$options['q']}%";
            $params[':qLast'] = "%{$options['q']}";
            $params[':q'] = $options['q'];
        }

        if( !empty($options['status']) ){

            if( is_array($options['status']) ){
                $statusID = '';
                foreach ($options['status'] as $key => $value) {
                    $statusID .= !empty($statusID) ? ',':'';
                    $statusID .= $value;
                }

                $condition .= !empty($condition) ? ' AND ':'';
                $condition .= "booking.status IN({$statusID})";
            }
            else{
                $condition .= !empty($condition) ? ' AND ':'';
                $condition .= "booking.status=:status";
                $params[':status'] = $options['status'];
            }
        }

        if( !empty($options['country'])  ){
            $condition .= !empty($condition) ? ' AND ':'';
            $condition .= "series.country_id=:country";
            $params[':country'] = $options['country'];
        }

        if( !empty($options['city'])  ){
            $condition .= !empty($condition) ? ' AND ':'';
            $condition .= "series.city_id=:city";
            $params[':city'] = $options['city'];
        }

        if( !empty($options['series'])  ){
            $condition .= !empty($condition) ? ' AND ':'';
            $condition .= "series.ser_id=:series";
            $params[':series'] = $options['series'];
        }
        if( !empty($options['sales'])  ){
            $condition .= !empty($condition) ? ' AND ':'';
            $condition .= "booking.user_id=:sales";
            $params[':sales'] = $options['sales'];
        }
        
        if( !empty($options['startDate']) && !empty($options['endDate']) ){

            $condition .= !empty($condition) ? ' AND ':'';
            $condition .= "(booking.create_date BETWEEN :startDate AND :endDate)";

            $params[':startDate'] = date('Y-m-d', strtotime($options['startDate']));
            $params[':endDate'] = date('Y-m-d', strtotime($options['endDate']));
        }

        /*  */
        $sort = $options['sort'];
        if( !empty($sort) ){
            switch ($sort) {
                case 'create': $sort='booking.create_date'; break;
                case 'start_date': $sort='period.per_date_start'; break;
                // case 'expired': $sort='period.per_date_start'; break;
            }
        }


        $limit = !empty($options['limit']) && !empty($options['page']) ? $this->limited( $options['limit'], $options['page'] ):'';
        $orderby = $this->orderby( $sort, $options['dir'] );
        $where = !empty($condition) ? "WHERE {$condition}":'';
        $sql = "SELECT SQL_CALC_FOUND_ROWS {$this->_field} FROM {$this->_table} {$where} {$orderby} {$limit}";
        // $arr['sql'] = $sql;
        // echo $sql; die;
        $results = $this->db->select($sql, $params);


        $sth = $this->db->prepare("SELECT FOUND_ROWS() as total");
        $sth->execute();
        $found_rows = $sth->fetch( PDO::FETCH_ASSOC );
        $arr['total'] = $found_rows['total'];
        $arr['items'] = $this->buildFrag( $results, $options );

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

        foreach (array('agen_lname', 'agen_position') as $key ) {
            $data[$key] = str_replace('-', '', $data[$key]);
            $data[$key] = str_replace('.', '', $data[$key]);
        }

        $data['detail'] = $this->getDetail($data['code']);


        $data['pax_total'] = 0;
        $data['traveler'] = array();
        foreach ($data['detail'] as $key => $value) {

            $keyname = strtolower($value['book_list_name']);
            $keyname = str_replace(' ', '_', $keyname);

            if( in_array($value['book_list_name'], array('Adult', 'Child', 'Child No bed', 'Joinland')) ){ // 'Infant', 
                $data['pax_total']+= $value['book_list_qty'];
            }

            $data['traveler'][$keyname] = $value['book_list_qty'];
        }


        $data['user_name'] = trim("{$data['user_fname']} {$data['user_lname']}");
        if( !empty($data['user_nickname']) ){
            $data['user_name'] .= " ({$data['user_nickname']})";
        }

        $data['agen_name'] = trim("{$data['agen_fname']} {$data['agen_lname']}");
        $data['status_arr'] = $this->query('system')->booking_getStatus($data['status']); 


        $data['createby_name'] = $data['createby_nickname'];
        if( empty($data['createby_name']) && !empty($data['createby_fname']) ){
            $data['createby_name'] = $data['createby_fname'];
        }

        /*if( !empty($data['agen_position']) ){
            $data['agen_name'] .= " ({$data['agen_position']})";
        }*/

        /*if( !empty($data['agen_com_province']) && empty($data['company_location_province']) ){
            $data['company_location_province'] = $data['agen_com_province'];
        }
        unset($data['agen_com_province']);*/

        return $data;
    }
    public function getDetail($code)
    {
        return $this->db->select("SELECT * FROM booking_list WHERE book_code=:code ORDER BY create_date DESC", array(':code'=>$code));
    }

	public function insert(&$data)
	{
        $this->db->insert($this->_obj, $data);
        $data['id'] = $this->db->lastInsertId();
	}

	public function update($id, $data)
	{
		$this->db->update($this->_obj, $data, "{$this->_prefixField}id={$id}");
	}

    /*public function delete($id)
    {
        $this->db->delete( $this->_obj, "{$this->_prefixField}id={$id}" );
    }*/


    public function salesList()
    {
        $results = $this->db->select("SELECT 
              `user_id` as id
            , `user_fname`
            , `user_lname`
            , `user_nickname`

            FROM `user` WHERE `status`=1 AND group_id IN(3,5,7) ORDER BY user_fname");


        $items = array();
        foreach ($results as $key => $value) {
            $name = trim($value['user_fname']);
            $value['user_lname'] = trim($value['user_lname'], '-');

            if( !empty(trim($value['user_lname'])) ){
                $name .= " ". trim($value['user_lname']);
            }

            $value['user_nickname'] = trim($value['user_nickname'], '-');
            if( !empty(trim($value['user_nickname'])) ){
                $name .= " (". trim($value['user_nickname']).')';
            }

            $items[] = array(
                'id' => $value['id'],
                'name' => $name
            );
        }
        return $items;
    }
    
    public function agencyList()
    {
        return $this->db->select("
            SELECT 
                  company.agen_com_id as id
                , company.`agen_com_name` as name
                , SUM(1) as total_agency

            FROM `agency_company` as company 
                LEFT JOIN agency ON company.agen_com_id=agency.agency_company_id 

            WHERE company.status=1 AND agency.status=1
            GROUP BY company.agen_com_id HAVING total_agency>0 
            ORDER BY company.agen_com_name
        ");
    }

    public function agencySalesList($options=array())
    {
        foreach (array('company') as $key) {
            if( isset($_REQUEST[$key]) ){
                $options[$key] = $_REQUEST[$key];
            }
        }
        /*$options = array_merge(array(
            'page' => isset($_REQUEST['page'])? $_REQUEST['page']:1,
            'limit' => isset($_REQUEST['limit'])? $_REQUEST['limit']:50,
            'more' => true,

            'sort' => isset($_REQUEST['sort'])? $_REQUEST['sort']: 'updated',
            'dir' => isset($_REQUEST['dir'])? $_REQUEST['dir']: 'DESC',
            
            'time'=> isset($_REQUEST['time'])? $_REQUEST['time']:time(),
            
        ), $options);*/

        $condition = '`status`=:status';
        $params = array(':status'=>1);

        if( !empty($options['company']) ){
            $condition .= !empty($condition) ? ' AND ':'';
            $condition .= "`agency_company_id`=:company";
            $params[':company'] = $options['company'];
        }

        $where = !empty($condition) ? "WHERE {$condition}":'';

        $results = $this->db->select("SELECT 
              `agen_id` as id
            , `agen_fname`
            , `agen_lname`
            , agency_company_id

        FROM `agency` {$where} ORDER BY agen_fname", $params);

        $items = array();
        foreach ($results as $key => $value) {
            $name = trim($value['agen_fname']);

            $value['agen_lname'] = trim($value['agen_lname'], '-');
            if( !empty(trim($value['agen_lname'])) ){
                $name .= " ". trim($value['agen_lname']);
            }

            $items[] = array(
                'id' => $value['id'],
                'name' => $name,
                'company_id' => $value['agency_company_id']
            );
        }
        return $items;
    }


    // 
    public function setDatePayment($date, $deposit, $mode=0, $now='')
    {
        $now = $now===''?date('Y-m-d H:i'):$now;
        $countdown = $this->fn->q('time')->Countdown($date, false, $now);

        $countdown_str = '';
        if( $countdown==0 ){
            $countdown_str = 'วันนี้';
        }
        if( $countdown==1 ){
            $countdown_str = "พรุ่งนี้";
        }else if($countdown>1){
            $countdown_str = "อีก {$countdown} วัน";
        }


        $arr = array(
            'trave' => array(
                'date' => date('Y-m-d', strtotime($date)),
                'countdown' => $countdown,
                'countdown_str' => $countdown_str,
            ),
            'deposit' => array(
                'date' => '',
                'value' => 0,
            ),
            
        );
        
        switch($mode){
            case 0:  

                $DayOfGo = $this->fn->q('time')->DateDiff( date("Y-m-d"), $date );
                if( $DayOfGo > 31 ){ //32 day
                    $arr['deposit']['date'] = date("Y-m-d 18:00", strtotime("+2 day"));
                    $arr['deposit']['value'] = $deposit;
                    $arr['fullpayment']['date'] = date('Y-m-d 18:00', strtotime("-30 day", strtotime($arr['trave']['date'])));

                }elseif ( $DayOfGo > 13 ){ //14 - 31 day
                    $arr['fullpayment']['date'] = date("Y-m-d 18:00", strtotime("+2 day"));
                }elseif($DayOfGo >7){ //13 - 8 day
                    $arr['fullpayment']['date'] = date("Y-m-d 18:00", strtotime("+1 day"));
                }elseif($DayOfGo >3){ // 4 -7 day
                    $arr['fullpayment']['date'] = date("Y-m-d H:i:s", strtotime("+12 hour"));
                }
                else{
                    $arr['fullpayment']['date'] = date("Y-m-d H:i:s", strtotime("+3 hour"));
                }

            break;
            case 1: 
                $arr['fullpayment']['date'] = date("Y-m-d H:i:s", strtotime("+6 hour"));
            break;

            case 2:  
                $arr['fullpayment']['date'] = date("Y-m-d H:i:s", strtotime("+12 hour"));
            break;
        }

        if( !empty($arr['deposit']['date']) ){
            
            $countdown = $this->fn->q('time')->Countdown($arr['deposit']['date'], false, $now);
            $arr['deposit']['countdown'] = $countdown;

            $arr['deposit']['countdown_str'] = '';

            if( $countdown == 1 ){
                $arr['deposit']['countdown_str'] = "วันพรุ่งนี้ ก่อน ".date('H:i', strtotime($arr['deposit']['date']));
                // $arr['deposit']['countdown_str'] = "อีก {$countdown} ชม.";
            }
            else if( $countdown > 1 ){
                $arr['deposit']['countdown_str'] = "อีก {$countdown} วัน";
            }
            else{

                $timestamp = strtotime($arr['deposit']['date']);
                $difference = $timestamp - time();

                if( $difference < 86400 ){
                    $difference = round($difference / 3600);

                    $arr['deposit']['countdown_str'] = "อีก {$difference} ชม.";
                }
            
            }
        }

        if( !empty($arr['fullpayment']['date']) ){
            $countdown = $this->fn->q('time')->Countdown($arr['fullpayment']['date'], true, $now);
            $arr['fullpayment']['countdown'] = $countdown;

            $arr['fullpayment']['countdown_str'] = '';

            if( $countdown['days'] == 0 ){
                $arr['fullpayment']['countdown_str'] = "อีก {$countdown['hours']} ชม.";
            }
            if( $countdown['days'] == 1 ){
                $arr['fullpayment']['countdown_str'] = "วันพรุ่งนี้ ก่อน ".date('H:i', strtotime($arr['fullpayment']['date']));
            }
            if( $countdown['days'] > 1 ){
                $arr['fullpayment']['countdown_str'] = "อีก {$countdown['days']} วัน";
            }
        }

        return $arr;
    }


    public function prefixNumber()
    {
        $sth = $this->db->prepare("SELECT * FROM prefixnumber LIMIT 1");
        $sth->execute();

        return $sth->fetch( PDO::FETCH_ASSOC );
    }
    public function prefixNumberUpdate($id, $data) {
        $this->db->update('prefixnumber', $data, "`prefix_id`={$id}");
    }

    public function getPromotion( $date ){
        $sth = $this->db->prepare("SELECT COALESCE(SUM(pro_discount),0) AS discount FROM promotions WHERE (pro_start_date <= :datenow AND pro_end_date >= :datenow) AND pro_status='enabled' LIMIT 1");
        $sth->execute( array( ':datenow' => $date ) );

        $fdata = $sth->fetch( PDO::FETCH_ASSOC );

        if( $sth->rowCount()==1 ){
            return $fdata["discount"];
        } return 0;
    }




    public function status($id=null)
    {
        $status = array();
        $status[0] = array('id'=>'00', 'name'=>'จอง', 'type'=>'booking', 'color' => '#58ceb1', 'count'=>0, 'is_booking'=>1);
        $status[5] = array('id'=>'05', 'name'=>'W/L', 'type'=>'booking', 'color' => '', 'count'=>0);
        $status[10] = array('id'=>'10', 'name'=>'แจ้ง Invoice', 'type'=>'booking', 'color' => '#f763eb', 'count'=>0, 'is_booking'=>1);
        $status[20] = array('id'=>'20', 'name'=>'DEP(PT)', 'desc'=>'ชำระเงินมัดจำไม่ครบ', 'type'=>'booking', 'color' => '#8bb8f1', 'is_payment'=>1, 'count'=>0, 'is_booking'=>1);
        $status[25] = array('id'=>'25', 'name'=>'DEP', 'desc'=>'ชำระเงินมัดจำครบ', 'type'=>'booking', 'color' => '#2f80e7', 'is_payment'=>1, 'count'=>0, 'is_booking'=>1);
        $status[30] = array('id'=>'30', 'name'=>'FP(PT)', 'desc'=>'ชำระเงินเต็มจำนวนไม่ครบ', 'type'=>'booking', 'color' => '#43d967', 'is_payment'=>1, 'count'=>0, 'is_booking'=>1);
        $status[35] = array('id'=>'35', 'name'=>'FP', 'desc'=>'ชำระเงินเต็มจำนวนครบ', 'type'=>'booking', 'color' => '#1e983b', 'is_payment'=>1, 'count'=>0, 'is_booking'=>1);
        $status[40] = array('id'=>'40', 'name'=>'CXL', 'desc'=>'ยกเลิกการจอง', 'type'=>'cancel', 'color' => '#ec2121', 'count'=>0);
        $status[50] = array('id'=>'50', 'name'=>'จอง/WL', 'desc'=>'W/L', 'type'=>'waitlist', 'color' => '#564aa3', 'count'=>0);
        $status[55] = array('id'=>'55', 'name'=>'แจ้งชำระเงิน', 'type'=>'booking', 'color' => '#ff902b', 'count'=>0);
        $status[60] = array('id'=>'60', 'name'=>'ปฏิเสธการชำระเงิน', 'color' => '#f05050', 'type'=>'cancel', 'count'=>0);

        if( $id!=null ){
            return $status[$id];
        }
        else{
            return $status;
        }

    }

}
