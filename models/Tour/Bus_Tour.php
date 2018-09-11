<?php

class Bus_Tour extends Model
{
	public function __construct() {
		parent::__construct();
    }
    
    private $_objtable = 'bus_list';
    private $_table = "bus_list";
    private $_select = "
          bus_id as id
        , per_id
        , bus_qty as seat 
        , bus_no as no
        , bus_options as options
        , bus_status as status
    ";

    
    private $_prefixField = 'bus_';

    public function findByPeriodNo($period, $no, $options=array(), $_dataPeriod=array())
    {   
        $this->_dataPeriod = $_dataPeriod;
        $sth = $this->db->prepare("SELECT {$this->_select} FROM {$this->_table} WHERE per_id=:period AND bus_no=:no LIMIT 1");
        $sth->execute(array(
            ':period' => $period,
            ':no' => $no,
        ));

        return $sth->rowCount()==1 ? $this->convert( $sth->fetch( PDO::FETCH_ASSOC ), $options ): array();
    }
    public function lists($options=array(), $_dataPeriod=array())
    {
        $this->_dataPeriod = $_dataPeriod;

    	$condition = "";
        $params = array();

        if( isset($options['period']) ){
            $condition .= !empty($condition) ? ' AND ':'';
            $condition .= "per_id=:period";
            $params[':period'] = $options['period'];
        }

    	$where = !empty($condition) ? "WHERE {$condition}":'';
    	$sql = "SELECT {$this->_select} FROM {$this->_table} {$where} ORDER BY bus_no ASC";
        return $this->buildFrag( $this->db->select($sql, $params), $options );
    }

    /* -- convert data -- */
    public function buildFrag($results, $options=array()) {
        $data = array();
        foreach ($results as $key => $value) { if( empty($value) ) continue; $data[] = $this->convert( $value, $options ); }
        return $data;
    }
    public function convert($data, $options=array()){

    	$opt = json_decode($data['options'], 1);
        $opt = !empty($opt) ? $opt: array();
        
        $opt['room_of_types'] = $this->_defaultRoomOfTypes();
        

        $data['status_arr'] = $this->getStatus( $data['status'] );

        if( !isset($opt['price_values']) ){
            $opt['price_values'] = array();
            
            if(!empty($this->_dataPeriod['per_price_1'])){
                $opt['commission'][] = array('name'=>'Adult', 'value'=> $this->_dataPeriod['per_price_1'] );
            }

            if(!empty($this->_dataPeriod['per_price_2'])){
                $opt['commission'][] = array('name'=>'Child', 'value'=> $this->_dataPeriod['per_price_2'] );
            }

            if(!empty($this->_dataPeriod['per_price_3'])){
                $opt['commission'][] = array('name'=>'Chlid No bed.', 'value'=> $this->_dataPeriod['per_price_3'] );
            }
        }
        

        // print_r($this->_dataPeriod); die;

        // $data['commission'] = $this->_defaultCommission();
        if( !isset($opt['commission']) ){
            $opt['commission'] = array();

            if(!empty($this->_dataPeriod['com_company_agency'])){
                $opt['commission'][] = array('name'=>'Com Agency', 'value'=> $this->_dataPeriod['com_company_agency'] );
            }

            if(!empty($this->_dataPeriod['com_agency'])){
                $opt['commission'][] = array('name'=>'Com Sales', 'value'=> $this->_dataPeriod['com_agency'] );
            }
        }


        if( !isset($opt['infant']) ){
            $opt['infant'][] = !empty($this->_dataPeriod['infant'])? $this->_dataPeriod['infant']: 0;
        }

        if( !isset($opt['joinland']) ){
            $opt['joinland'] = !empty($this->_dataPeriod['joinland'])? $this->_dataPeriod['joinland']: 0;
        }

        if( !isset($opt['single_charge']) ){
            $opt['single_charge'] = !empty($this->_dataPeriod['single_charge'])? $this->_dataPeriod['single_charge']: 0;
        }

        if( !isset($opt['discounts']) ){
            $opt['discounts'][] = $this->_dataPeriod['discount'];
        }

        if( !isset($opt['cancel_mode']) ){
            $opt['cancel_mode'] = $this->_dataPeriod['cancel_mode'];
        }

        // print_r($options); die;

        $data['options'] = $opt;

        $data['autocancel_arr'] = $this->getAutocancel( $opt['cancel_mode'] );

        if( !empty($options['with_booking']) ){


            $bookingList = $this->query('booking')->lists( array('period'=>$data['per_id'], 'bus'=>$data['no']) );

            $bookingStatusList = $this->query('booking')->status();
            $bookingTotal = 0;
            $data['booking'] = array(
                // 'total' => count($bookingList),
                'items' => $bookingList,
                
                'booking' => array(),
                'wishlist' => array(),
                'fullpayment' => array(),

                'options' => array(
                    'period' => $data['per_id'],
                    'bus' => $data['no'],
                ),
            );

            foreach ($bookingList as $key => $value) {

                foreach ($bookingStatusList as $i => $val) {
                    
                    if($value['status']==$i){

                        $bookingStatusList[$i]['count'] ++;

                        if(!empty($val['is_booking'])){
                            $bookingTotal++;

                            $data['booking']['booking'][] = $value;
                        }

                        break;
                    }
                }


                if( $value['status']==50 ){
                    $data['booking']['wishlist'][] = $value;
                }


                if( $value['status']==35 ){
                    $data['booking']['fullpayment'][] = $value;
                }
            }

            $data['booking']['total'] = $bookingTotal;
            $data['booking']['status'] = $bookingStatusList;

            $data['seat_balance'] = $data['seat']-$bookingTotal;
        }

        return $data;
    }

    public function insert(&$data)
	{
		$this->db->insert($this->_objtable, $data);
        $data['id'] = $this->db->lastInsertId();
	}

	public function delete($id)
	{
		$this->db->delete( $this->_objtable, "{$this->_prefixField}id={$id}" );
	}

	public function update($id, $data)
	{
		$this->db->update( $this->_objtable, $data, "{$this->_prefixField}id={$id}" );
	}



    public function _defaultPriceValue()
    {
        $lists = array();
        $lists[] = array('id'=>'adult', 'name'=> 'Adult', 'key'=> 'per_price_1', 'is_pax'=>1);
        $lists[] = array('id'=>'child', 'name'=> 'Child', 'key'=> 'per_price_2', 'is_pax'=>1);
        $lists[] = array('id'=>'childNoBed', 'name'=> 'Child No Bed', 'key'=> 'per_price_3', 'is_pax'=>1);


        $lists[] = array('id'=>'infant', 'name'=> 'Infant', 'key'=> 'per_price_4');
        $lists[] = array('id'=>'joinland', 'name'=> 'Joinland', 'key'=> 'per_price_5', 'is_pax'=>1);


        // $lists[] = array('id'=>'single_charge', 'name'=> 'Single Charge', 'key'=> 'single_charge', 'is_pax'=>1);

        return $lists;
    }

    public function _defaultCommission()
    {
        $lists = array();
        $lists[] = array('name'=> 'Com Agency', 'key'=> 'com_company_agency', 'is_pax'=>1);
        $lists[] = array('name'=> 'Com Sales', 'key'=> 'com_agency', 'is_pax'=>1);

        return $lists;
    }

    public function _defaultDiscount()
    {
        $lists = array();
        $lists[] = array('name'=> 'โปรไฟไหม้', 'key'=> 'discount', 'is_pax'=>1);

        return $lists;
    }

    public function _defaultStatus()
    {
        $lists = array();
        $status[1] = array('id'=>1, 'name' => 'เปิดจอง', 'color'=>'#4CAF50', 'display'=>1);
        $status[2] = array('id'=>2, 'name' => 'เต็ม', 'color'=>'#4CAF50');
        $status[3] = array('id'=>3, 'name' => 'ปิดทัวร์', 'color'=>'#F44336', 'display'=>1);
        $status[4] = array('id'=>4, 'name' => 'หมดเวลา', 'color'=>'#605988' );

        $status[9] = array('id'=>9, 'name' => 'ระงับ', 'color'=>'#4CAF50', 'display'=>1 );
        $status[10] = array('id'=>10, 'name' => 'ตัดตั๋ว', 'color'=>'#4CAF50', 'display'=>1 );

        return $status;
    }
    public function getStatus($id)
    {
        $status = $this->_defaultStatus();
        return !empty($status[$id]) ? $status[$id]: array();
    }

    public function _defaultAutoCancel()
    {
        $status[] = array('id'=>0, 'name' => 'Normal');
        $status[] = array('id'=>1, 'name' => '6 Hr.');
        $status[] = array('id'=>2, 'name' => '12 Hr.');

        return $status;
    }
    public function getAutocancel($id)
    {
        $status = $this->_defaultAutoCancel();
        return !empty($status[$id]) ? $status[$id]: array();
    }

    public function _defaultRoomOfTypes()
    {
        $rooms = array();
        $rooms[] = array('id'=>'twin', 'name'=>'Twin', 'quota'=>2);
        $rooms[] = array('id'=>'double', 'name'=>'Double', 'quota'=>2);
        $rooms[] = array('id'=>'triple', 'name'=>'Triple', 'quota'=>3);
        $rooms[] = array('id'=>'tripletwin', 'name'=>'Triple(Twin)', 'quota'=>3);
        $rooms[] = array('id'=>'single', 'name'=>'Single', 'quota'=>1, 'is_singlecharge'=>true);

        return $rooms;
    }
}