<?php


/* -- import --*/

require_once 'Location/Country.php';
require_once 'Location/City.php';


class Location_Model extends Model{

	public function __construct() {
		parent::__construct();

		$this->country = new Location_Country();
		$this->city = new Location_City();
	}

	public function countryList()
	{
		return $this->db->select("SELECT country_id as id, country_name as name FROM country WHERE status=1 ORDER BY country_name");
	}
}
