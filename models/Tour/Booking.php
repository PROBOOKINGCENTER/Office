<?php


class Booking_Tour extends Model
{
	public function __construct() {
		parent::__construct();
    }


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
            'pager' => isset($_REQUEST['pager'])? $_REQUEST['pager']:1,
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
}
