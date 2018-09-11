<?php

class Airline extends Model
{
	function __construct()
	{
		parent::__construct();
	}

	private $_tableName = 'airline';
	private $_prefixField = 'air_';

	public function find( $options=array() )
	{
		$condition = "";
        $params = array();

        if( isset($options['status']) ){
        	$condition .= !empty($condition)? ' AND ':'';
        	$condition .= "status=:display";
        	$params[':display'] = $options['status'];
        }
        $where = !empty($condition) ? "WHERE {$condition}":'';
		return $this->buildFrag($this->db->select("SELECT * FROM {$this->_tableName} {$where} ORDER BY air_name", $params));
	}
	public function get($id)
	{
		$sth = $this->db->prepare("SELECT * FROM {$this->_tableName} WHERE air_id=:id LIMIT 1");
        $sth->execute( array( ':id' => $id  ) );
        return $sth->rowCount()==1 ? $this->convert($sth->fetch( PDO::FETCH_ASSOC )): array();
	}

	public function insert(&$data)
	{
		$this->db->insert($this->_tableName, $data);
        $data['id'] = $this->db->lastInsertId();
	}

	public function update($id, $data)
	{
		$this->db->update($this->_tableName, $data, "`air_id`={$id}");
	}

    public function delete($id)
    {
        $this->db->delete($this->_tableName, "`air_id`={$id}" );
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
}