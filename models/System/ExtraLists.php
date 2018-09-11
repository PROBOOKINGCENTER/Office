<?php

class ExtraLists extends Model
{
	function __construct()
	{
		parent::__construct();
	}

	private $_tableName = 'extralists';

	public function find( $options=array() )
	{
		$condition = "";
        $params = array();

        if( isset($options['enabled']) ){
        	$condition .= !empty($condition)? ' AND ':'';
        	$condition .= "enabled=:display";
        	$params[':display'] = $options['enabled'];
        }
        $where = !empty($condition) ? "WHERE {$condition}":'';
		return $this->db->select("SELECT * FROM {$this->_tableName} {$where} ORDER BY name", $params);
	}
	public function get($id)
	{
		$sth = $this->db->prepare("SELECT * FROM {$this->_tableName} WHERE id=:id LIMIT 1");
        $sth->execute( array( ':id' => $id  ) );
        return $sth->rowCount()==1 ? $sth->fetch( PDO::FETCH_ASSOC ): array();
	}

	public function insert(&$data)
	{
		$this->db->insert($this->_tableName, $data);
        $data['id'] = $this->db->lastInsertId();
	}

	public function update($id, $data)
	{
		$this->db->update($this->_tableName, $data, "`id`={$id}");
	}

    public function delete($id)
    {
        $this->db->delete($this->_tableName, "`id`={$id}" );
    }
}