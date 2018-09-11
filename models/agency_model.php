<?php

require_once 'Agency/Company.php';
require_once 'Agency/Sales.php';

class Agency_Model extends Model
{
	public function __construct() {
		parent::__construct();

        $this->company = new Agency_Company();
        $this->sales = new Agency_Sales();
    }

}
