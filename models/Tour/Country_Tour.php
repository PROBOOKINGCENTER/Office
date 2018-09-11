<?php

class Country_Tour extends Model
{
	public function __construct() {
		parent::__construct();
    }

    private $_objType = 'country';
    private $_table = 'country';
    private $_field = '*';
    private $_prefixField = 'country_';
    private $_uploadFolder = 'tour/location/country/';

   
    public function get($id)
	{
		$sth = $this->db->prepare("SELECT {$this->_field} FROM {$this->_table} WHERE {$this->_prefixField}id=:id LIMIT 1");
        $sth->execute( array( ':id' => $id  ) );
        return $sth->rowCount()==1 ? $this->convert( $sth->fetch( PDO::FETCH_ASSOC ) ): array();
	}
    public function lists($options=array())
    {
        $results = $this->find( array_merge(array(
            'unlimit' => true,
        ), $options) );
        return $results['items'];
    }
    public function find($options=array())
    {
        foreach (array('status') as $key) {
            if( isset($_REQUEST[$key]) ){
                $options[$key] = $_REQUEST[$key];
            }
        }

        $options = array_merge(array(
            'more' => false,

            'limit' => isset($_REQUEST['limit'])? $_REQUEST['limit']: 50,
            'page' => isset($_REQUEST['page'])? $_REQUEST['page']: 1,

            'sort' => isset($_REQUEST['sort'])? $_REQUEST['sort']: 'name',

            'time'=> isset($_REQUEST['time'])? $_REQUEST['time']:time(),

        ), $options);

        if( !empty($options['unlimit']) ){
            unset($options['page']); unset($options['limit']);
        }

        $condition = ""; $having  = '';
        $params = array();

        if( isset($options['status']) ){
            $condition .= !empty($condition) ? ' AND ':'';
            $condition .= "status=:status";
            $params[':status'] = $options['status'];
        }

        $limit = !empty($options['limit']) && !empty($options['page']) ? $this->limited( $options['limit'], $options['page'] ):'';
        $orderby = !empty($options['sort'])?  "ORDER BY {$this->_prefixField}{$options['sort']}":'';
        $where = !empty($condition) ? "WHERE {$condition}":'';

        $groupby = "";
        $having = !empty($having) ? "HAVING {$having}":'';
        $sql = "SELECT SQL_CALC_FOUND_ROWS {$this->_field} FROM {$this->_table} {$where} {$groupby} {$having} {$orderby} {$limit}";
        $results = $this->db->select($sql, $params);

        $sth = $this->db->prepare("SELECT FOUND_ROWS() as total");
        $sth->execute();
        $found_rows = $sth->fetch( PDO::FETCH_ASSOC );
        $arr['total'] = $found_rows['total'];
        $arr['items'] = $this->buildFrag( $results, $options );


        if( !empty($options['page']) && !empty($options['limit']) ){
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
        $data['permit']['del'] = 1;


        if( !empty($data['img']) ){
            $data['image_url'] = UPLOADS.$this->_uploadFolder.$data['img'];
        }

        return $data;
    }



	public function insert(&$data)
	{
		$this->db->insert($this->_objType, $data);
        $data['id'] = $this->db->lastInsertId();
	}

	public function update($id, $data)
	{
		$this->db->update($this->_objType, $data, "{$this->_prefixField}id={$id}");
	}

    public function delete($id)
    {
        $this->db->delete( $this->_objType, "{$this->_prefixField}id={$id}" );
    }



    public function insertImage($userfile, $id, $typefile='img', $uid)
    {                        
        $source = $userfile['tmp_name'];
        $filename = $userfile['name'];

        $filename = $this->fn->q('file')->createName($filename, $id, $typefile, $uid );

        if( copy($source, WWW_UPLOADS.$this->_uploadFolder.$filename) ){
            // 
            $this->update( $id, array(
                'country_img' => $filename,
            ));
        }
    }
    public function deleteImage($id, $filename)
    {
        $filename = strtolower(strrchr($filename, '/'));
        // $ext = $this->fn->q('file')->getExtension($filename);
        $source =  WWW_UPLOADS.$this->_uploadFolder.$filename;
        // $path =  UPLOADS.$folder.$filename;

        if( file_exists($source) ){
            unlink($source);
            $this->update($id, array('country_img'=>''));
        }

    }
}
