<?php

class Search_Controller extends Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        
    	$this->view->render('design/hotel-list');
    }
}
