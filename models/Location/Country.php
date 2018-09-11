<?php

class Location_Country extends Model
{
	public function __construct() {
		parent::__construct();
    }


    private $_table = 'country';
    private $_field = '*';
    private $_prefixField = 'country_';


    public function get($id)
	{
		$sth = $this->db->prepare("SELECT {$this->_field} FROM {$this->_table} WHERE {$this->_prefixField}id=:id LIMIT 1");
        $sth->execute( array( ':id' => $id  ) );
        return $sth->rowCount()==1 ? $this->convert( $sth->fetch( PDO::FETCH_ASSOC ) ): array();
	}

	public function findById($id)
	{
		return $this->get($id);
	}
    public function lists($options=array())
    {
        $results = $this->find( $options );
        return $results['items'];
    }
    public function find($options=array())
    {
    	$options = array_merge(array(
            'more' => true,

            'sort' => isset($_REQUEST['sort'])? $_REQUEST['sort']: 'name',
            'dir' => isset($_REQUEST['dir'])? $_REQUEST['dir']: 'ASC',

            'time'=> isset($_REQUEST['time'])? $_REQUEST['time']:time(),

        ), $options);

        $condition = "";
        $params = array();

        if( isset($options['status']) ){
            $condition .= !empty($condition)? ' AND ':'';
        	$condition .= "status=:display";
        	$params[':display'] = $options['status'];
        }

        $arr['total'] = $this->db->count($this->_table, $condition, $params);

        $limit = !empty($options['limit']) && !empty($options['pager']) ? $this->limited( $options['limit'], $options['pager'] ):'';
        $orderby = $this->orderby( $this->_prefixField.$options['sort'], $options['dir'] );
        $where = !empty($condition) ? "WHERE {$condition}":'';
        $sql = "SELECT {$this->_field} FROM {$this->_table} {$where} {$orderby} {$limit}";

        $arr['items'] = $this->buildFrag( $this->db->select($sql, $params), $options );

        if( !empty($options['limit']) ){
        	if( ($options['pager']*$options['limit']) >= $arr['total'] ) $options['more'] = false;
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
        $data['permit']['del'] = 1;
        return $data;
    }

	public function insert(&$data)
	{
		$this->db->insert($this->_table, $data);
        $data['id'] = $this->db->lastInsertId();
	}

	public function update($id, $data)
	{
		$this->db->update($this->_table, $data, "{$this->_prefixField}id={$id}");
	}

    public function delete($id)
    {
        $this->db->delete( $this->_table, "{$this->_prefixField}id={$id}" );
    }


}
