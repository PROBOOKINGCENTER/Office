<?php

class Agency_Sales extends Model
{
	public function __construct() {
		parent::__construct();
    }

    private $_objType = 'agency';
    private $_table = 'agency LEFT JOIN agency_company company ON agency.agency_company_id=company.agen_com_id';
    private $_field = '
    	  agency.agen_id

    	, agency.agen_user_name as username

    	, agency.agen_fname
    	, agency.agen_lname
    	, agency.agen_nickname
    	, agency.agen_position

    	, agency.agen_email
    	, agency.agen_tel
    	, agency.agen_line_id

    	, agency.create_date
        , agency.update_date
        , agency.lastvisit

        , agency.status
        , agency.agen_role
        , agency.agen_star

    	, agency.agency_company_id as company_id
        , company.agen_com_name as company_name
    	, company.agen_com_name_th as company_name_th
    ';
    private $_prefixField = 'agen_';

    public function get($id)
    {
        return $this->findById($id);
    }
    public function findById($id)
	{
		$sth = $this->db->prepare("SELECT {$this->_field} FROM {$this->_table} WHERE {$this->_prefixField}id=:id LIMIT 1");
        $sth->execute( array( ':id' => $id  ) );
        return $sth->rowCount()==1 ? $this->convert( $sth->fetch( PDO::FETCH_ASSOC ) ): array();
	}
    public function find($options=array())
    {
        foreach (array('q', 'status', 'star', 'role') as $key) {
            if( isset($_REQUEST[$key]) ){
                $options[$key] = $_REQUEST[$key];
            }
        }

    	$options = array_merge(array(
            'more' => true,

            'limit' => isset($_REQUEST['limit'])? $_REQUEST['limit']: 50,
            'page' => isset($_REQUEST['page'])? $_REQUEST['page']: 1,

            'sort' => isset($_REQUEST['sort'])? $_REQUEST['sort']: 'update_date',
            'dir' => isset($_REQUEST['dir'])? $_REQUEST['dir']: 'DESC',

            'time'=> isset($_REQUEST['time'])? $_REQUEST['time']:time(),

        ), $options);

        $condition = "";
        $params = array();

        if( isset($options['status']) ){
            $condition .= !empty($condition) ? ' AND ':'';
            $condition .= "`agency`.`status`=:status";
            $params[':status'] = $options['status'];
        }

        if( !empty($options['role']) ){
            $condition .= !empty($condition) ? ' AND ':'';
            $condition .= "`agen_role`=:role";
            $params[':role'] = $options['role'];
        }

        if( !empty($options['star']) ){
            $condition .= !empty($condition) ? ' AND ':'';
            $condition .= "`agen_star`=:star";
            $params[':star'] = $options['star'];
        }
        
        if( !empty($options['q']) ){
            $condition .= !empty($condition) ? ' AND ':'';
            $condition .= "(agen_fname LIKE :qLower OR agen_fname LIKE :qUpper OR agen_user_name='{$options['q']}' OR agen_email LIKE '%{$options['q']}' OR agen_tel LIKE '%{$options['q']}' OR agen_line_id LIKE '%{$options['q']}' OR agen_nickname LIKE '%{$options['q']}')";
            $params[':qLower'] = '%'.(strtolower($options['q'])).'%';
            $params[':qUpper'] = '%'.(strtoupper($options['q'])).'%';
        }


        $arr['total'] = $this->db->count($this->_table, $condition, $params);

        $limit = !empty($options['limit']) && !empty($options['page']) ? $this->limited( $options['limit'], $options['page'] ):'';
        $orderby = $this->orderby( $options['sort'], $options['dir'] );
        $where = !empty($condition) ? "WHERE {$condition}":'';
        $sql = "SELECT {$this->_field} FROM {$this->_table} {$where} {$orderby} {$limit}";

        $arr['items'] = $this->buildFrag( $this->db->select($sql, $params), $options );


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
        foreach ($results as $key => $value) { if( empty($value) ) continue; $data[] = $this->convert( $value ); }
        return $data;
    }
    public function convert($data){

        $data = $this->__cutPrefixField($this->_prefixField, $data);
        // $data['permit']['del'] = 1;
        
        $data['name'] = trim("{$data['fname']} {$data['lname']}");

        $data['email_str'] = !empty($data['email']) ? trim($data['email']): '-';
        $data['tel_str'] = !empty($data['tel']) ? trim($data['tel']): '-';


        $date['update_date_str'] = '-';
        if( !empty($data['update_date']) && $data['update_date']!='0000-00-00 00:00:00' ){
            $time = strtotime($data['update_date']);

            $data['update_date_str'] = date('j', $time);
            $data['update_date_str'] .= ' '.date('M', $time);
            $data['update_date_str'] .= ' '.date('Y', $time);
            $data['update_date_str'] .= '<div class="fss fcg">'.date('H:i', $time).'</div>';

        }


        $data['lastvisit_str'] = '-';
        if( !empty($data['lastvisit']) && $data['lastvisit']!='0000-00-00 00:00:00' ){
            $time = strtotime($data['lastvisit']);

            $data['lastvisit_str'] = date('j', $time);
            $data['lastvisit_str'] .= ' '.date('M', $time);
            $data['lastvisit_str'] .= ' '.date('Y', $time);
            $data['lastvisit_str'] .= '<div class="fss fcg">'.date('H:i', $time).'</div>';

        }


        $data['status_arr'] = $this->getStatus( $data['status'] );
        $data['status_str'] = !empty($data['status_arr']) ? array('text'=>$data['status_arr']['name'], 'css'=>$data['status_arr']['css']): array();


        $data['nickname_str'] = !empty($data['nickname']) ? "{$data['nickname']}": '';


        $role_arr = $this->getRole( $data['role'] );
        $data['role_id'] = $role_arr['id'];
        $data['role_name'] = $role_arr['name'];;
        

        return $data;
    }

	public function insert(&$data)
	{
		$this->db->insert($this->_objType, $data);
        $data['id'] = $this->db->lastInsertId();
	}

	public function update($id, $data)
	{
		$this->db->update($this->_objType, $data, "`agen_id`={$id}");
	}

    public function delete($id)
    {
        $this->db->delete($this->_objType, "`agen_id`={$id}" );
    }



    public function admin_roles()
    {
        $role['admin'] = array('id'=>'admin', 'name' => 'Admin', 'css' => array( 'background-color'=> '#2196f3', 'color' => '#fff' ));
        $role['sales'] = array('id'=>'sales', 'name' => 'Sales', 'css' => array( 'background-color'=> '#9e9e9e', 'color' => '#fff' ));

        return $role;
    }
    public function getRole($id)
    {
        $arr = $this->admin_roles(); 
        return !empty($arr[$id]) ? $arr[$id]: null;
    }

    public function status()
    {
        $status[] = array('id'=>0, 'name' => 'รอการตรวจสอบ', 'css' => array( 'background-color'=> '#ffc107', 'color' => '#fff' ));
        $status[] = array('id'=>1, 'name' => 'ใช้งาน', 'css' => array( 'background-color'=> '#2196f3', 'color' => '#fff' ));
        $status[] = array('id'=>2, 'name' => 'ระงับ', 'css' => array( 'background-color'=> '#9e9e9e', 'color' => '#fff' ));


        foreach ($status as $key => $value) {
            $status[$key]['count'] = $this->db->count($this->_objType, "`status`=:status", array(':status'=>$value['id']));
        }

        return $status;
    }
    public function getStatus($id)
    {
        $arr = $this->status(); 
        return !empty($arr[$id]) ? $arr[$id]: null;
    }

    public function is_user($txt){
        $c = $this->db->count($this->_objType, "(agen_user_name=:txt AND agen_user_name!='') OR (agen_email=:txt AND agen_email!='')", array(':txt'=>$txt));
        return $c;
    }



    public function companyList()
    {
        return $this->db->select("SELECT agen_com_id as id, agen_com_name as name FROM agency_company WHERE status=1 ORDER BY agen_com_name");
    }
}
