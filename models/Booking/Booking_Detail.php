<?php


class Booking_Detail extends Model
{
	public function __construct() {
		parent::__construct();
    }


    private $_tableObj = 'booking_list';
    private $_table = 'booking_list';
    private $_field = '*';
    private $_prefixField = '';


    public function insert(&$data)
	{
		$this->db->insert($this->_tableObj, $data);
        $data['id'] = $this->db->lastInsertId();
	}
	
}