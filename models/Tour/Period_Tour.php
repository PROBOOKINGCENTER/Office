<?php

require_once 'Bus_Tour.php';

class Period_Tour extends Model
{
	public function __construct() {
		parent::__construct();

        $this->bus = new Bus_Tour();
    }
    
    private $_objtable = 'period';
    private $_table = '

        period
            LEFT JOIN bus_list bus ON bus.per_id=period.per_id

    ';
    private $_field = "

          period.per_id
        , period.per_date_start
        , period.per_date_end
        
        , period.ser_id

        , period.cancel_mode

        , period.per_price_1
        , period.per_price_2
        , period.per_price_3
        , period.per_price_4
        , period.per_price_5
        , period.single_charge
        , period.per_discount


        , period.per_url_word
        , period.per_url_pdf
        
        , period.per_com_agency
        , period.per_com_company_agency

        , ( CASE 
            WHEN DATE(now()) > period.per_date_start THEN 4
            WHEN period.status=1 THEN 1
            WHEN period.status=2 THEN 2
            WHEN period.status=3 THEN 3
            WHEN period.status=9 THEN 9
            WHEN period.status=10 THEN 10
            ELSE 0
        END ) as state

    ";
    private $_prefixField = 'per_';
    private $_uploadFolder = 'travel/';

    public function get($id, $option=array())
    {
        $table = "{$this->_table} LEFT JOIN series ON series.ser_id=period.ser_id";
        $field = "{$this->_field}, series.ser_id, series.ser_name, series.ser_code, series.ser_deposit as deposit";

        $condition = "period.per_id=:id";
        $params = array(':id'=>$id);

        if( !empty($option['bus']) ){
            $condition .= !empty($condition) ? ' AND ':'';
            $condition .= "bus.bus_no={$option['bus']}";
            // $params[':bus'] = $option['bus'];
        }

        $where = !empty($condition) ? "WHERE {$condition}":'';
        // echo "SELECT {$field} FROM {$table} {$where} LIMIT 1"; die;
        $sth = $this->db->prepare("SELECT {$field} FROM {$table} {$where} LIMIT 1");
        $sth->execute($params);

        $item = $sth->rowCount()==1 ? $this->convert( $sth->fetch( PDO::FETCH_ASSOC ), $option ): array();
        // print_r($item); die;

        // if( !empty($item) ){
        //     if( !empty($option['bus']) && !empty($item['busList'][0]) ){
        //         $item['bus'] = $item['busList'][0];
        //         unset($item['busList']);

        //         $item['bus_str'] = $option['bus'];
        //     }
        // }

        return  $item;
	}
    
    public function lists($options=array())
    {
        $options['unlimit'] = true;
        $results = $this->find($options);
        return $results['items'];
    }
    public function find($options=array())
    {
        foreach (array('series', 'state', 'startDate', 'endDate', 'with_booking') as $key) {
            if( isset($_REQUEST[$key]) ){
                $options[$key] = $_REQUEST[$key];
            }
        }

        $options = array_merge(array(
            'more' => true,

            'limit' => isset($_REQUEST['limit'])? $_REQUEST['limit']: 50,
            'pager' => isset($_REQUEST['pager'])? $_REQUEST['pager']: 1,

            'sort' => isset($_REQUEST['sort'])? $_REQUEST['sort']: 'per_date_start ASC',
            // 'dir' => isset($_REQUEST['dir'])? $_REQUEST['dir']: 'DESC',

            'time'=> isset($_REQUEST['time'])? $_REQUEST['time']:time(),

        ), $options);

        // $options['state'] = array(0,1,2,3,9,10);

        $condition = ""; $condition2  = '';
        $params = array();

        if( isset($options['series']) ){
            $condition .= !empty($condition) ? ' AND ':'';
            $condition .= "ser_id=:series";
            $params[':series'] = $options['series'];
        }

        
        if( isset($options['state']) ){
            $condition2 .= !empty($condition2) ? ' AND ':'';

            if( is_array($options['state']) ){
                $state = '';
                foreach ($options['state'] as $value) {
                    $state .= $state!='' ? ',':'';
                    $state .= $value;
                }

                $condition2 .= "state IN ({$state})";
            }
            else{
                $condition2 .= "state=:state";
                $params[':state'] = $options['state'];
            }
        }

        if( !empty($options['startDate']) && !empty($options['endDate']) ){

            $condition .= !empty($condition) ? ' AND ':'';
            $condition .= "(period.per_date_start BETWEEN :startDate AND :endDate)";

            $params[':startDate'] = date('Y-m-d', strtotime($options['startDate']));
            $params[':endDate'] = date('Y-m-d', strtotime($options['endDate']));
        }


        $limit = '';
        if( empty($options['unlimit']) ){
            $limit = !empty($options['limit']) && !empty($options['pager']) ? $this->limited( $options['limit'], $options['pager'] ):'';
        }
        $orderby = !empty($options['sort']) ? "ORDER BY {$options['sort']}": ''; // $this->orderby( $options['sort'], $options['dir'] );
        $where = !empty($condition) ? "WHERE {$condition}":'';
        $having = !empty($condition2) ? "HAVING {$condition2}":'';
        $sql = "SELECT SQL_CALC_FOUND_ROWS {$this->_field} FROM {$this->_table} {$where} GROUP BY period.per_id {$having} {$orderby} {$limit}";
        $results = $this->db->select($sql, $params);
        // echo $sql; //die;

        $sth = $this->db->prepare("SELECT FOUND_ROWS() as total");
        $sth->execute();
        $found_rows = $sth->fetch( PDO::FETCH_ASSOC );
        $arr['total'] = $found_rows['total'];
        $arr['items'] = $this->buildFrag( $results, $options );

        if( empty($options['unlimit']) ){
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
        foreach ($results as $key => $value) { if( empty($value) ) continue; $data[] = $this->convert( $value, $options ); }
        return $data;
    }
    public function convert($data, $options=array()){

        $data = $this->__cutPrefixField($this->_prefixField, $data);
        // $data['permit']['del'] = 1;

        $busno = '';
        if( !empty($options['bus']) ){
            $busno = " AND `bus_no`={$options['bus']}";
        }

        $data['status_arr'] = $this->getStatus( $data['state'] );
        $data['auto_cancel_mode'] = $this->get_auto_cancel_mode( $data['cancel_mode'] );


        if( !empty($data['url_word']) ){

            $filename = $data['url_word'];
            $ext = explode('/', $filename);
            if( count($ext)>1 ){
                $filename = strtolower(substr(strrchr($filename,"/"),1));
            }

            $data['word_url'] = UPLOADS.$this->_uploadFolder.$filename;
        }

        if( !empty($data['url_pdf']) ){

            $filename = $data['url_pdf'];
            $ext = explode('/', $filename);
            if( count($ext)>1 ){
                $filename = strtolower(substr(strrchr($filename,"/"),1));
            }

            $data['pdf_url'] = UPLOADS.$this->_uploadFolder.$filename;
        }

        if( !empty($options['bus']) ){
            $data['bus'] = $this->bus->findByPeriodNo( $data['id'], $options['bus'], $options, $data );
        }
        else{

            $opt = array('period'=>$data['id']);

            if( !empty($options['with_booking']) ){
                $opt['with_booking'] = 1;
            }

            $data['busList'] = $this->bus->lists( $opt, $data );
        }
        

        $data['date_str'] = $this->fn->q('time')->eventDate( $data['date_start'], $data['date_end'] );

        // date('j', strtotime($data['date_start'])).'-'.date('j M Y', strtotime($data['date_end'])); // $this->fn->q('time')->str_event_date( $data['date_start'], $data['date_end'], 1 );

        $data['price'] = $data['price_1'];
        $data['price_str'] = number_format($data['price_1']);


        $view_stype = !empty($options['view_stype']) ? $options['view_stype']: 'convert';
        if( !in_array($view_stype, array('bucketed')) ) $view_stype = 'convert';
        return $view_stype=='convert'? $data: $this->{$view_stype}( $data );
    }
    public function bucketed($data, $options=array()) {

        return array(
            'id'=> $data['id'],
            'text'=> $data['name'],
            // 'subtext' => '',
            // "category"=> '',
            // "code"=> $data['code'],
            "status"=> $data['status'],
            "image"=> $data['image_url'],            
        );
    }

	public function insert(&$data)
	{
		$this->db->insert($this->_objtable, $data);
        $data['id'] = $this->db->lastInsertId();
	}

	public function update($id, $data)
	{
		$this->db->update($this->_objtable, $data, "{$this->_prefixField}id={$id}");
	}


    public function remove($data)
    {
        if( !empty($data['busList']) ){
            foreach ($data['busList'] as $i => $bus) {
                $this->bus->delete($bus['id']);
            }
        }

        if(!empty($data['url_word'])  ){
            $this->removeFile($data['id'], $data['url_word'], 'per_url_word');
        }

        if(!empty($data['url_pdf'])  ){
            $this->removeFile($data['id'], $data['url_pdf'], 'per_url_pdf');
        }

        $this->delete($data['id']);
    }
    public function delete($id)
    {
        $this->db->delete( $this->_objtable, "{$this->_prefixField}id={$id}" );
    }


    public function status()
    {
        $status[1] = array('id'=>1, 'name' => 'เปิดจอง', 'color'=>'#4CAF50' );
        $status[2] = array('id'=>2, 'name' => 'เต็ม', 'color'=>'#4CAF50' );
        $status[3] = array('id'=>3, 'name' => 'ปิดทัวร์', 'color'=>'#F44336' );
        $status[4] = array('id'=>4, 'name' => 'หมดเวลา', 'color'=>'#605988' );

        $status[9] = array('id'=>9, 'name' => 'ระงับ', 'color'=>'#4CAF50' );
        $status[10] = array('id'=>10, 'name' => 'ตัดตั๋ว', 'color'=>'#4CAF50' );

        return $status;
    }
    public function getStatus($id)
    {
        $arr = $this->status(); 
        return !empty($arr[$id]) ? $arr[$id]: null;
    }


    public function auto_cancel_mode()
    {
        $status[] = array('id'=>0, 'name' => 'Normal');
        $status[] = array('id'=>1, 'name' => '6 Hr.');
        $status[] = array('id'=>2, 'name' => '12 Hr.');

        return $status;
    }
    public function get_auto_cancel_mode($id)
    {
        $arr = $this->auto_cancel_mode(); 
        return !empty($arr[$id]) ? $arr[$id]: null;
    }
    


    public function upload( $id, $options )
    {
        $source = $options['userfile']['tmp_name'];
        $filename = $options['userfile']['name'];

        $filename = $this->fn->q('file')->createName($filename, "{$id}_{$this->_objtable}", $options['type'], $options['user_id'] );

        if( copy($source, WWW_UPLOADS.$this->_uploadFolder.$filename) ){
            // 
            $dataPost[$options['field']] = $filename;
            $this->update( $id, $dataPost);
        }
    }
    public function removeFile($id, $filename, $field='')
    {
        $ext = explode('/', $filename);
        if( count($ext)>1 ){
            $filename = strtolower(substr(strrchr($filename,"/"),1));
        }

        $source =  WWW_UPLOADS.$this->_uploadFolder.$filename;
        if( file_exists($source) ){
            unlink($source);

            if( !empty($field) ){
                $data[$field] = '';
                $this->update($id, $data);
            }
        }
    }
}