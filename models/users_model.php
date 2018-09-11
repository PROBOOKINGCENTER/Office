<?php

class Users_Model extends Model{

    public function __construct() {
        parent::__construct();
    }

    private $_objType = "user";
    private $_table = "`user` LEFT JOIN `group` ON `user`.`group_id`=`group`.`group_id`";
    private $_field = "
          user.user_id
        , user.user_name as username
        
        , user.user_fname
        , user.user_lname
        , user.user_nickname
        
        , user.user_address
        , user.user_email
        , user.user_tel
        , user.user_line_id

        , user.status
        , user.user_lang
        , user.user_mode

        , user.update_date
        , user.user_lastvisit

        , `group`.`group_id` as role_id 
        , `group`.`group_name` as role_name
    ";
    private $_prefixField = "user_";
    public function createPassword($text){
        return substr(md5($text), 0, 20);
    }

    public function is_user($text){
        $c = $this->db->count($this->_objType, "(user_name=:txt AND user_name!='') OR (user_email=:txt AND user_email!='')", array(':txt'=>$text));
        return $c;
    }
    public function is_name($text) {
        return $this->db->count($this->_objType, "name='{$text}'");
    }


    /* -- actions -- */
    public function insert(&$data) {

        $data["create_date"] = date('c');
        $data["update_date"] = date('c');

        if( isset($data["{$this->_prefixField}password"]) ){
            $data["{$this->_prefixField}password"] =  $this->createPassword( $data["{$this->_prefixField}password"] );
        }

        $this->db->insert($this->_objType, $data);
        $data['id'] = $this->db->lastInsertId();
    }
    public function update($id, $data) {
        $data["update_date"] = date('c');
        $this->db->update($this->_objType, $data, "`user_id`={$id}");
    }
    public function delete($id) {
        $this->db->delete($this->_objType, "{$this->_prefixField}id={$id}");
    }


    /* -- find -- */
    public function get($id, $options=array())
    {
        return $this->findById( $id, $options );
    }
    public function findById($id, $options=array()){

        $condition = "user.user_id=:id";
        $params = array(':id' => $id);

        if( isset($options['status']) ){
            $condition .= !empty($condition) ? ' AND ':'';
            $condition .= "user.status=:status";
            $params[':status'] = $options['status'];
        }

        $where = !empty($condition) ? "WHERE {$condition}":'';
        $sth = $this->db->prepare("SELECT {$this->_field} FROM {$this->_table} {$where} LIMIT 1");
        $sth->execute( $params );
        return $sth->rowCount()==1 ? $this->convert( $sth->fetch( PDO::FETCH_ASSOC ) ): array();
    }
    public function find( $options=array() ) {

        foreach (array('q', 'status', 'role') as $key) {
            if( isset($_REQUEST[$key]) ){
                $options[$key] = $_REQUEST[$key];
            }
        }

        $options = array_merge(array(
            'page' => isset($_REQUEST['page'])? $_REQUEST['page']:1,
            'limit' => isset($_REQUEST['limit'])? $_REQUEST['limit']:50,
            'more' => true,

            'sort' => isset($_REQUEST['sort'])? $_REQUEST['sort']: 'update_date',
            'dir' => isset($_REQUEST['dir'])? $_REQUEST['dir']: 'DESC',

            'time'=> isset($_REQUEST['time'])? $_REQUEST['time']:time(),

            // 'enabled' => isset($_REQUEST['enabled'])? $_REQUEST['enabled']:1,

        ), $options);

        $date = date('Y-m-d H:i:s', $options['time']);

        $condition = "";
        $params = array();


        if( !empty($options['role']) ){
            $condition .= !empty($condition) ? ' AND ':'';
            $condition .= "`group`.`group_id`=:role";
            $params[':role'] = $options['role'];
        }

        if( !empty($options['status']) ){
            $condition .= !empty($condition) ? ' AND ':'';
            $condition .= "user.status=:status";
            $params[':status'] = $options['status'];
        }
        else{
            $condition .= !empty($condition) ? ' AND ':'';
            $condition .= "user.status IN(1,2)";
        }

        if( isset($options['q']) ){
            $condition .= !empty($condition) ? ' AND ':'';
            $condition .= "(user.user_fname LIKE '%{$options['q']}%' OR user.user_name='{$options['q']}' OR user.user_email LIKE '%{$options['q']}' OR user.user_tel LIKE '%{$options['q']}')";
        }

        $arr['total'] = $this->db->count($this->_table, $condition, $params);

        $limit = !empty($options['unlimit']) ? '': $this->limited( $options['limit'], $options['page'] );
        $orderby = $this->orderby( $options['sort'], $options['dir'] );
        $where = !empty($condition) ? "WHERE {$condition}":'';
        $sql = "SELECT {$this->_field} FROM {$this->_table} {$where} {$orderby} {$limit}";

        $arr['items'] = $this->buildFrag( $this->db->select($sql, $params ), $options );

        if( ($options['page']*$options['limit']) >= $arr['total'] ) $options['more'] = false;

        $arr['options'] = $options;

        return $arr;
    }


    /* -- convert data -- */
    public function buildFrag($results, $options=array()) {
        $data = array();
        foreach ($results as $key => $value) {
            if( empty($value) ) continue;
            $data[] = $this->convert( $value );
        }

        return $data;
    }
    public function convert($data){

        $data = $this->__cutPrefixField($this->_prefixField, $data);
        $data['access'] = $this->setAccess( $data['role_id'] );
        $data['name'] = trim("{$data['fname']} {$data['lname']}");

        // $data['permit']['del'] = 1;

        switch ($data['lang']) {
            case 'th': $data['lang_str'] = 'ภาษาไทย'; break;
            default: $data['lang_str'] = 'English (United States)'; break;
        }

        return $data;
    }
    public function setAccess($id)  {
        $access = array();
        if( $id == 1 ){
            $access = array(1);
        }

        return $access;
    }


    /* -- Login -- */
    public function login($user, $pass){

        // echo $user, $this->createPassword($pass); die;
        $sth = $this->db->prepare("SELECT user_id as id FROM {$this->_objType} WHERE (user_name=:login AND user_password=:pass AND user.status=1) OR (user_email=:login AND user_password=:pass AND user.status=:status)");
        $sth->execute( array(
            ':login' => $user,
            ':pass' => $this->createPassword( $pass ),
            ':status' => 1,
        ) );

        $fdata = $sth->fetch( PDO::FETCH_ASSOC );
        return $sth->rowCount()==1 ? $fdata['id']: false;
    }
    public function loginWithGoogle($id, $email) {

        $sth = $this->db->prepare("SELECT user_id as id FROM {$this->_objType} WHERE (user_google_id=:login AND user_email=:mail AND user.status=1)");

        $sth->execute( array( ':login' => $id, ':mail' => $email ) );

        $fdata = $sth->fetch( PDO::FETCH_ASSOC );
        return $sth->rowCount()==1 ? $fdata['id']: false;
    }


    /* -- admin roles -- */
    public function admin_roles() {
        $role = $this->db->select("SELECT group_id as id, group_name as name FROM `group` ORDER BY group_name");

        foreach ($role as $key => $value) {
            $role[$key]['count'] = $this->db->count($this->_objType, "`group_id`=:id", array(':id'=>$value['id']));
        }

        return $role;
    }

    public function status()
    {
        $status[1] = array('id'=>1, 'name' => 'ใช้งาน', 'css' => array( 'background-color'=> '#2196f3', 'color' => '#fff' ));
        $status[2] = array('id'=>2, 'name' => 'ระงับ', 'css' => array( 'background-color'=> '#9e9e9e', 'color' => '#fff' ));
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
}
