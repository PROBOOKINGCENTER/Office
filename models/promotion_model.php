<?php

class Promotion_Model extends Model{

    public function __construct() {
        parent::__construct();
    }

    private $_objType = "promotions";
    private $_table = "promotions";
    private $_field = "*";
    private $_prefixField = "pro_";

    /* -- actions -- */
    public function insert(&$data) {

        $data['pro_created'] = date('c');
        $data['pro_updated'] = date('c');

        $this->db->insert($this->_objType, $data);
        $data['id'] = $this->db->lastInsertId();
    }
    public function update($id, $data) {
        $data["pro_updated"] = date('c');
        $this->db->update($this->_objType, $data, "`pro_id`={$id}");
    }
    public function delete($id) {

        $this->db->delete('promotions_join_period', "`promotion_id`={$id}", $this->db->count("promotions_join_period", "`promotion_id`={$id}"));
        $this->db->delete($this->_objType, "{$this->_prefixField}id={$id}");
    }


    /* -- find -- */
    public function get($id, $options=array())
    {
        return $this->findById( $id, $options );
    }
    public function findById($id, $options=array()){

        $condition = "pro_id=:id";
        $params = array(':id' => $id);

        $where = !empty($condition) ? "WHERE {$condition}":'';
        $sth = $this->db->prepare("SELECT {$this->_field} FROM {$this->_table} {$where} LIMIT 1");
        $sth->execute( $params );
        return $sth->rowCount()==1 ? $this->convert( $sth->fetch( PDO::FETCH_ASSOC ) ): array();
    }
    public function find( $options=array() ) {

        foreach (array('q') as $key) {
            if( isset($_REQUEST[$key]) ){
                $options[$key] = $_REQUEST[$key];
            }
        }

        $options = array_merge(array(
            'page' => isset($_REQUEST['page'])? $_REQUEST['page']:1,
            'limit' => isset($_REQUEST['limit'])? $_REQUEST['limit']:50,
            'more' => true,

            'sort' => isset($_REQUEST['sort'])? $_REQUEST['sort']: 'start_date',
            'dir' => isset($_REQUEST['dir'])? $_REQUEST['dir']: 'DESC',

            'time'=> isset($_REQUEST['time'])? $_REQUEST['time']:time(),

        ), $options);

        if( !empty($options['unlimit']) ){
            unset($options['limit']); unset($options['page']);
        }

        $date = date('Y-m-d H:i:s', $options['time']);

        $condition = "";
        $params = array();


        if( !empty($options['q']) ){
            $condition .= !empty($condition) ? ' AND ':'';
            $condition .= "(pro_name LIKE '%{$options['q']}%')";
        }

        $limit = !empty($options['limit']) && !empty($options['page']) ? $this->limited( $options['limit'], $options['page'] ):'';
        $orderby = $this->orderby( $this->_prefixField.$options['sort'], $options['dir'] );
        $where = !empty($condition) ? "WHERE {$condition}":'';
        $sql = "SELECT SQL_CALC_FOUND_ROWS {$this->_field} FROM {$this->_table} {$where} {$orderby} {$limit}";
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
        foreach ($results as $key => $value) {
            if( empty($value) ) continue;
            $data[] = $this->convert( $value );
        }

        return $data;
    }
    public function convert($data){

        $data = $this->__cutPrefixField($this->_prefixField, $data);
        $data['permit']['del'] = 1;

        $data['date_str'] = $this->fn->q('time')->str_event_date( $data['start_date'], $data['end_date'], true );
        $data['discount_str'] = number_format($data['discount']);


        if( !empty($data['file_image']) ){
            $data['image_url'] = URL.'media/promotion/banner/'.$data['id'].'.jpg';
        }

        $items =  $this->db->select("SELECT 

              pmt.period_id
            , pmt.id as _id
            , pmt.bus

            , country.country_id
            , country.country_name

            , series.ser_id as serie_id
            , series.ser_name as serie_name
            , series.ser_code as serie_code

            , period.per_id as id
            , period.per_date_start as start_date
            , period.per_date_end as end_date
            , bus.bus_qty as seat

            FROM promotions_join_period as pmt
                LEFT JOIN ( period 
                    INNER JOIN ( series 
                        LEFT JOIN country ON country.country_id=series.country_id
                    ) ON series.ser_id=period.ser_id
                    INNER JOIN bus_list as bus ON bus.per_id=period.per_id
                ) ON pmt.period_id=period.per_id
            WHERE pmt.promotion_id=:id", array(':id'=>$data['id']));

        $data['items'] = array();
        foreach ($items as $key => $value) {

            $value['period_name'] = $this->fn->q('time')->str_event_date( $value['start_date'], $value['end_date'], true );
            $data['items'][] = $value;
        }
        return $data;
    }



    public function insertItems(&$data)
    {
        $this->db->insert('promotions_join_period', $data);
        $data['id'] = $this->db->lastInsertId();
    }
    public function deleteItem($id)
    {
        $this->db->delete('promotions_join_period', "`id`={$id}");
    }


    public function deleteImage($filename)
    {
        $folder = 'promotion';
        $filename = strtolower(strrchr($filename, '/'));

        $ext = $this->fn->q('file')->getExtension($filename);

        $source =  WWW_UPLOADS.$folder.$filename;
        // $path =  UPLOADS.$folder.$filename;

        if( file_exists($source) ){
            unlink($source);
        }

    }
}
