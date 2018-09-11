<?php


class Agency_Company extends Model
{
	public function __construct() {
		parent::__construct();
    }


    private $_table = 'agency_company';
    private $_field = '*';
    private $_prefixField = 'agen_com_';

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
        foreach (array('q', 'status', 'guarantee') as $key) {
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
        	$condition .= "`status`=:status";
        	$params[':status'] = $options['status'];
        }
        if( !empty($options['guarantee']) ){
            $condition .= !empty($condition) ? ' AND ':'';
            $condition .= "agen_com_guarantee=:guarantee";
            $params[':guarantee'] = $options['guarantee'];
        }

        if( !empty($options['q']) ){
            $condition .= !empty($condition) ? ' AND ':'';
            $condition .= "(agen_com_name LIKE :qLower OR agen_com_name LIKE :qUpper OR agen_com_username='{$options['q']}' OR agen_com_tel LIKE '%{$options['q']}')";
            $params[':qLower'] = '%'.(strtolower($options['q'])).'%';
            $params[':qUpper'] = '%'.(strtoupper($options['q'])).'%';
        }

        $arr['total'] = $this->db->count($this->_table, $condition, $params);


        $limit = !empty($options['limit']) && !empty($options['page']) ? $this->limited( $options['limit'], $options['page'] ):'';
        $orderby = $this->orderby( $options['sort'], $options['dir'] );
        $where = !empty($condition) ? "WHERE {$condition}":'';
        $sql = "SELECT {$this->_field} FROM {$this->_table} {$where} {$orderby} {$limit}";
        // echo $sql; die;

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

        $data['last_connected_str'] = '-';
        if( !empty($data['last_connected'])  ){
            $time = strtotime($data['last_connected']);

            $data['last_connected_str'] = date('j', $time);
            $data['last_connected_str'] .= ' '. $this->fn->q('time')->month( date('n', $time) );
            $data['last_connected_str'] .= ' '.date('Y', $time);
            $data['last_connected_str'] .= '<div class="fss fcg">'.date('H:i', $time).'</div>';
        }


        $data['update_date_str'] = '-';
        if( !empty($data['update_date']) && $data['update_date']!='0000-00-00 00:00:00' ){
            $time = strtotime($data['update_date']);

            $data['update_date_str'] = date('j', $time);
            $data['update_date_str'] .= ' '. $this->fn->q('time')->month( date('n', $time) );
            $data['update_date_str'] .= ' '.date('Y', $time);
            $data['update_date_str'] .= '<div class="fss fcg">'.date('H:i', $time).'</div>';
        }


        $data['website_str'] = '-';;
        if( !empty($data['website']) ){

            $text_website = $data['website'];
            $text_website = str_replace(' ', '', $text_website);
            $text_website = str_replace('http://', '', $text_website);
            $text_website = str_replace('https://', '', $text_website);
            $text_website = trim($text_website, '/');

            $e = explode('.', $text_website);
            if( $e[0] != 'www' ){
                $text_website = 'www.'.$text_website;
            }


            $website = $text_website;
            if (filter_var($website, FILTER_VALIDATE_URL) === false) {
                $website = "http://".$website;
            }

            $url = '~(?:(https?)://([^\s<]+)|(www\.[^\s<]+?\.[^\s<]+))(?<![\.,:])~i'; 
            $data['website_str'] = preg_replace($url, '<a href="$0" target="_blank" title="'.$text_website.'" style="overflow: hidden;text-overflow: ellipsis;white-space: nowrap;max-width:180px;display:block;">'.$text_website.'</a>', $website);
        }

        $data['status_arr'] = $this->getStatus( $data['status'] );
        $data['status_str'] = array('text'=>$data['status_arr']['name'], 'css'=>$data['status_arr']['css']);
       
        $data['email_str'] = !empty($data['email']) ? $data['email']: '-';
        $data['tel_str'] = !empty($data['tel']) ? $data['tel']: '-';

        $data['guarantee_str'] = !empty($data['guarantee']) ? '<i class="icon-thumbs-up"></i>': '<i class="icon-thumbs-o-up"></i>';

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


    public function license_types()
    {
        return array( 0 => 
              array('id'=>1, 'name' => 'ต่างประเทศ INBOUND')
            , array('id'=>2, 'name' => 'ต่างประเทศ OUTBOUND')  
            , array('id'=>3, 'name' => 'ในประเทศ OUTBOUND')  
            , array('id'=>4, 'name' => 'เฉพาะพื้นที่')  
        );
    }

    public function city()
    {
        return $this->db->select("SELECT province_id as id, province_name as name FROM location_province ORDER BY province_name");
    }


    public function status()
    {
        $status[] = array('id'=>0, 'name' => 'รอการตรวจสอบ', 'css' => array( 'background-color'=> '#00bcd4', 'color' => '#fff' ));
        $status[] = array('id'=>1, 'name' => 'ใช้งาน', 'css' => array( 'background-color'=> '#2196f3', 'color' => '#fff' ));
        $status[] = array('id'=>2, 'name' => 'ระงับ', 'css' => array( 'background-color'=> '#9e9e9e', 'color' => '#fff' ));


        foreach ($status as $key => $value) {
            $status[$key]['count'] = $this->db->count($this->_table, "`status`=:status", array(':status'=>$value['id']));
        }

        return $status;
    }
    public function getStatus($id)
    {
        $arr = $this->status(); 
        return $arr[$id];
    }
}
