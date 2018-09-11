<?php

class Ui {

    function __construct() { }

    private $_query = array();
    public function frame($q='default')
    {
    	$path = "Ui/{$q}.php";
    	if( file_exists($path) ){

	    	if(array_key_exists($q, $this->_query)==false){
	            require_once $path;
	            $clsName = ucfirst($q) . '_Ui';
	            $this->_query[$q] = new $clsName;
	        }

	        return $this->_query[$q];
        }
    }
}