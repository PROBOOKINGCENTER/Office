<?php

class Payment_Model extends Model{

	public function __construct() {
		parent::__construct();
	}

	private $_obj = 'payment';
    private $_table = "

	    payment

            LEFT JOIN (booking 
                LEFT JOIN ( period
                    LEFT JOIN series ON period.ser_id=series.ser_id
                ) ON booking.per_id=period.per_id
            ) ON booking.book_id=payment.book_id
	    	
            LEFT JOIN bankbook ON bankbook.bankbook_id=payment.bankbook_id

	    	LEFT JOIN user as createby ON createby.user_id=payment.create_user_id
	        LEFT JOIN user as updateby ON updateby.user_id=payment.update_user_id

    ";
    private $_field = '
    	  payment.*

        , series.ser_name
        , series.ser_code
        
        , period.per_date_start as period_date_start
        , period.per_date_end as period_date_end

        , booking.invoice_code

    	, bankbook.bank_name
    	, bankbook.bankbook_code
    	, bankbook.bankbook_name
    	, bankbook.bankbook_branch

    	, createby.user_fname as createby_fname
        , createby.user_lname as createby_lname
        , createby.user_nickname as createby_nickname

        , updateby.user_fname as updateby_fname
        , updateby.user_lname as updateby_lname
        , updateby.user_nickname as updateby_nickname
    ';
    private $_prefixField = 'pay_';


	public function insert(&$data)
	{
        $this->db->insert($this->_obj, $data);
        $data['id'] = $this->db->lastInsertId();
	}
	public function update($id, $data)
	{
		$this->db->update($this->_obj, $data, "{$this->_prefixField}id={$id}");
	}



	public function lists($options=array())
	{	
		$options = array_merge(array(
			'unlimit' => 1
		), $options);
		$results = $this->find($options);

		return $results['items'];
	}
	public function find($options=array())
	{
		foreach (array('q', 'status', 'booking', 'book_status') as $key) {
            if( isset($_REQUEST[$key]) ){
                $options[$key] = $_REQUEST[$key];
            }
        }

		$options = array_merge(array(
            'more' => false,

            'limit' => isset($_REQUEST['limit'])? $_REQUEST['limit']: 50,
            'page' => isset($_REQUEST['page'])? $_REQUEST['page']: 1,

            'sort' => isset($_REQUEST['sort'])? $_REQUEST['sort']: 'create_date',
            'dir' => isset($_REQUEST['dir'])? $_REQUEST['dir']: 'DESC',

            'time'=> isset($_REQUEST['time'])? $_REQUEST['time']:time(),

        ), $options);

        if( !empty($options['unlimit']) ){
            unset($options['limit']); unset($options['page']);
        }


        /* condition */
        $condition = "";
        $params = array();

        if( !empty($options['q']) ){
            $condition .= !empty($condition) ? ' AND ':'';
            $condition .= "booking.book_code=:q OR series.ser_code=:q";
            $params[':q'] = $options['q'];
        }

        if( !empty($options['status']) ){

            if( is_array($options['status']) ){
            	// print_r($options['status']); die;
                $statusID = '';
                foreach ($options['status'] as $value) {
                    $statusID .= $statusID!=='' ? ',':'';
                    $statusID .= $value;
                }

                $condition .= !empty($condition) ? ' AND ':'';
                $condition .= "payment.status IN({$statusID})";
            }
            else{
                $condition .= !empty($condition) ? ' AND ':'';
                $condition .= "payment.status=:status";
                $params[':status'] = $options['status'];
            }
        }

        if( !empty($options['book_status']) ){

            if( is_array($options['book_status']) ){
                $statusID = '';
                foreach ($options['book_status'] as $value) {
                    $statusID .= $statusID!=='' ? ',':'';
                    $statusID .= $value;
                }

                $condition .= !empty($condition) ? ' AND ':'';
                $condition .= "payment.book_status IN({$statusID})";
            }
            else{
                $condition .= !empty($condition) ? ' AND ':'';
                $condition .= "payment.book_status=:book_status";
                $params[':book_status'] = $options['book_status'];
            }
        }

        if( !empty($options['booking']) ){
            $condition .= !empty($condition) ? ' AND ':'';
            $condition .= "payment.book_id=:booking";
            $params[':booking'] = $options['booking'];
        }


        $limit = !empty($options['limit']) && !empty($options['page']) ? $this->limited( $options['limit'], $options['page'] ):'';
        $orderby = $this->orderby( "{$this->_obj}.{$options['sort']}", $options['dir'] );
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
        	if( ($options['page']*$options['limit']) < $arr['total'] ) $options['more'] = true;
        }

        $arr['options'] = $options;
        return $arr;

	}


	/* -- convert data -- */
    public function buildFrag($results, $options=array()) {
        $data = array();
        foreach ($results as $key => $value) { if( empty($value) ) continue; $data[] = $this->convert( $value ); }
        return $data;
    }
    public function convert($data){

        $data = $this->__cutPrefixField($this->_prefixField, $data);

        $data['download_link'] = URL."download/payment/{$data['id']}";
        $data['preview_file_link'] = URL."media/payment/{$data['id']}";
        $data['status_arr'] = $this->getStatus( $data['status'] );

        $data['book_status_arr'] = $this->query('system')->booking_getStatus( $data['book_status'] );

        $data['createby_name'] = $data['createby_nickname'];
        if( empty($data['createby_name']) ){
        	$data['createby_name'] = trim("{$data['createby_fname']} {$data['createby_lname']}");
        }else{
        	$data['createby_name'] .= '('.trim("{$data['createby_fname']} {$data['createby_lname']}").')';
        }


        $data['period_date_str'] = date('j-', strtotime($data['period_date_start'])) . date('j M Y', strtotime($data['period_date_end']));

        return $data;
    }


    public function status()
    {
        $a = array();
        $a['0'] = array('id'=>'0', 'name'=>'รอตรวจสอบ', 'color' => '#ff902b');
        $a['1'] = array('id'=>'1', 'name'=>'ผ่านการตรวจสอบ', 'color' => '#27c24c');
        $a['9'] = array('id'=>'9', 'name'=>'ไม่ผ่านการตรวจสอบ', 'color' => '#ec2121');

        return $a;
    }
    public function getStatus($id)
    {
        $statusList = $this->status();
        return !empty($statusList[$id]) ? $statusList[$id]: array();
    }
   
}
