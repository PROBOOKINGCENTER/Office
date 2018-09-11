<?php


class Booking_Discount extends Model
{
	public function __construct() {
		parent::__construct();
    }


    private $_obj = 'booking_join_discount';
    private $_table = "booking_join_discount";

    private $_field = "*";
    private $_prefixField = 'disc_';


    public function insert(&$data)
	{
		$this->db->insert($this->_obj, $data);
        $data['id'] = $this->db->lastInsertId();
	}

    public function lists($options=array())
    {
        
        $options = array_merge(array(
            'more' => true,

            'limit' => isset($_REQUEST['limit'])? $_REQUEST['limit']: 50,
            'page' => isset($_REQUEST['pager'])? $_REQUEST['pager']: 1,

            'sort' => isset($_REQUEST['sort'])? $_REQUEST['sort']: 'disc_seq',

            'time'=> isset($_REQUEST['time'])? $_REQUEST['time']:time(),

        ), $options);

        if( !empty($options['unlimit']) ){
            unset($options['limit']); unset($options['page']);
        }


        $condition = ""; $having = "";
        $params = array();
        
        if( !empty($options['booking']) ){

            $condition .= !empty($condition) ? ' AND ':'';
            $condition .= "disc_bid=:booking";

            $params[':booking'] = $options['booking'];
        }


        $limit = !empty($options['limit']) && !empty($options['page']) ? $this->limited( $options['limit'], $options['page'] ):'';
        $orderby = "ORDER BY {$options['sort']}";


        $where = !empty($condition) ? "WHERE {$condition}":'';
        $having = !empty($having) ? "HAVING {$having}":'';
        $sql = "
            SELECT 

            SQL_CALC_FOUND_ROWS

            {$this->_field}

            FROM {$this->_table}
                
            {$where} {$orderby} {$limit}

        ";

        $results = $this->db->select($sql, $params);

        $sth = $this->db->prepare("SELECT FOUND_ROWS() as total");
        $sth->execute();
        $found_rows = $sth->fetch( PDO::FETCH_ASSOC );
        $arr['total'] = $found_rows['total'];
        $arr['items'] = $this->buildFrag( $results, $options );

        return $arr;
    }
    public function find($options=array())
    {
        $options = array_merge(array(
            'unlimit' => 1,
        ), $options);

        $results  = $this->lists( $options );

        return $results['items'];
    }


    /* -- convert data -- */
    public function buildFrag($results, $options=array()) {
        $data = array();
        foreach ($results as $key => $value) { if( empty($value) ) continue; $data[] = $this->convert( $value, $options ); }
        return $data;
    }
    public function convert($data, $options=array()){
        $data = $this->__cutPrefixField($this->_prefixField, $data);

        return $data;
    }
}